<?php

namespace App\Http\Controllers\Auction;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests\Auction\ItemRequest;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Category;
use App\College;
use Auth;
use Carbon\Carbon;
use Repositories\Contracts\ItemRepositoryInterface;
use Repositories\Contracts\ImageRepositoryInterface;
use Repositories\Contracts\UserRepositoryInterface;
use Repositories\Contracts\NotificationRepositoryInterface;



class ItemController extends Controller
{
    protected $items;
    protected $images;
    protected $users;
    protected $notifications;

    public function __construct(ItemRepositoryInterface $items, ImageRepositoryInterface $images, UserRepositoryInterface $users,NotificationRepositoryInterface $notifications)
    {
        $this->middleware('auth', ['except' => 'getShowItem']);
        $this->middleware('creater',['except' => ['getIndex','getCreateItem','postCreateItem','getShowItem','getEditItem','patchUpdateItem','destroyItem','postUploadItemImage','getAdmin','destroyImage','getAdminAPI','postComment','postReport','postRepost']]);
        $this->middleware('timeup',['except' => ['getIndex','getCreateItem','postCreateItem','getShowItem','getEditItem','patchUpdateItem','destroyItem','postUploadItemImage','getAdmin','destroyImage','getAdminAPI','postComment','postReport','postRepost']]);
        $this->middleware('profile',['except' => ['getIndex','getShowItem','getAdmin','getAdminAPI','postComment','postReport']]);
        $this->middleware('admin',['except' => ['getIndex','getCreateItem','postCreateItem','getShowItem','getAdmin','getAdminAPI','postBidItem','postComment','postReport','postFreeItem']]);
        $this->items = $items;
        $this->images = $images;
        $this->users = $users;
        $this->notifications = $notifications;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex($college,$auction,$type)
    {
        $colleges = College::all()/*->lists('name', 'id')*/;
        $college = College::where('acronym',$college);

        //拍賣or競投
        $itemtype = 0;
        $category = Category::where('type','bid_items')->get();
        if($auction=='seek'){
            $itemtype = 1;
            $category = Category::where('type','seek_items')->get();
        }

        $items = $college->first()->items()->where('disabled',0)->where('type',$itemtype)->where('end_time','>',Carbon::now());
        //$items = $this->items->all()->where('disabled',0)/*->where('end_time','<',Carbon::now())*/;
        //return dd($items->first()->end_time == Carbon::now());

        //判斷搜尋者性別做搜尋條件(traget的whereIN= =)
        //$this->items = $items;
        //$items = $this->items->whereIn('target',[0,1]);

        if(Auth::check()){
            $user = $this->users->find(Auth::id());
            if($user->gender==1)
                $items = $items->whereIn('target',[0,1]);
            else
                $items = $items->whereIn('target',[0,2]);
        }else
             $items = $items->whereIn('target',[0,1,2]);

        //分類搜尋
        /*$category_id =  \Request::input('category_id');*/
        //未分類個數
        $itemall = $items->get();

        if($type!='all' && $type!='my' && $type!='free'){
            if($auction=='bid')
                $category_id =  Category::where('type','bid_items')->where('en_name',$type)->first()->id;
            else
                $category_id =  Category::where('type','seek_items')->where('en_name',$type)->first()->id;
            $items = $items->where('category_id',$category_id);
        }
        elseif($type=='my')
            $items = Auth::user()->items()->where('type',$itemtype)->where('disabled',0)->where('end_time','>',Carbon::now())->groupBy('items.id');
        elseif($type=='free')
            $items = $items->where('free','1');

        $items = $items->orderBy('end_time','asc')->paginate(12);
        //return dd($items);
        return View('auctions.items.index', compact('items','category','type','colleges','auction','college','itemall'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreateItem($auction)
    {
        $type = 'bid_items';
        //競投
        if($auction=='seek')
            $type = 'seek_items';

        $category = Category::all()->where('type',$type)->lists('name', 'id');
        return View('auctions.items.create',compact('category','auction'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreateItem(ItemRequest $request,$auction)
    {
        //check category_id
        $type = 'bid_items';
        if($auction=='seek')
            $type = 'seek_items';
        $category = Category::where('type',$type)->where('id',$request->category_id);
        if($category->first()==null)
            return redirect()->back()->withErrors('無此分類');

        //Validator for checking rhat limit 3 file upload
        $start_time = Carbon::now();
        $end_time = Carbon::now()->addDays(30);
        //return dd($request->all());

        $image_num = count($request->image);
        if($image_num > 3) {
            return redirect()->back()->withInput()->withErrors('不能上傳超過三張圖片！');
        }

        $rules = [];
        $nbr = count($request->image) - 1;
        foreach(range(0, $nbr) as $index) {
            $messages = array(
            'image.' . $index.'.required'    => '請上傳圖片',
            'image.0.max'    => '圖片1大小不能超過10mb',
            'image.1.max'    => '圖片2大小不能超過10mb',
            'image.2.max'    => '圖片3大小不能超過10mb',
            'image.0.image'    => '檔案1只能為圖片',
            'image.1.image'    => '檔案2只能為圖片',
            'image.2.image'    => '檔案3只能為圖片',
             );

            if($auction=='bid')
                $rules['image.' . $index] = 'required|max:10240|image';
            else
                $rules['image.' . $index] = 'max:10240|image';
        }
        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
        }


        //find a user 
        $user = $this->users->find(Auth::id());
        // create a item
        $item = $user->item()->create($request->all());
        $item->start_time = $start_time;
        $item->end_time = $end_time;
        if($auction=='seek')
            $item->type = 1;
        $item->save();
        // create a album of item.
        $album = $item->albums()->create(['user_id' => Auth::id(), 'name' => $item->name]);
        // upload file and return file path
        $images = $this->images->uploadImage($request->all(), Auth::id(), 'images/auctions'); 
        
        // crate a images of album
        if($images!=null){
            foreach($images as $image) {
            $image = $album->images()->create([
                'file_name' => $image->basename,
                'file_mime' => $image->mime,
                'file_path'  => '/images/auctions/' . $image->basename,
                'description' => $request->description
            ]);
            }
        }

        $new = "";
        $free = "";
        if($request->new==1)
            $new = "[全新] ";
        if($request->free==1){
            $free = "[免費] ";
        }
        else
            $price = $request->price;
        $itemtype = "[拍賣] ";
        $priceyupe = "底價：";
        if($auction=='seek'){
            $itemtype = "[競投] ";
            $priceyupe = "起標價：";
        }
        $pricede= '';
        if($request->free==0)
            $pricede = $priceyupe."$".$price."\n";

        $message = $itemtype.$new.$free.$request->name."\n"."分類：".$item->category()->first()->name."\n".$pricede."描述："."\n".$request->description;
        $item_url = route('get.auction.item.show', ['auction' => $auction,'id' => $item->id]);
        $notification = $this->notifications->postToFacebookGroup($message,'EAAEPz1VMu8sBAGvggsZBd1CNLuKQShOQJ6UUdFwmTUpRVV0sWmZCo3Q32RU3c83bN98CvmQc9JVM5ZByT2CpfoxarCtbFB85uSCQZCjWZCsZAk2jO4dzZAUZC4cTfkBzTvaieIusKyrNJ2e9PrQ0MjZAdrcGA1XNEUS9aNyMC4Gen5gZDZD',$item_url,$user->college()->first()->group_id);
/*	//Auto Post to Group
    $message = 'Loyaus';
    $token ='EAAEPz1VMu8sBAGvggsZBd1CNLuKQShOQJ6UUdFwmTUpRVV0sWmZCo3Q32RU3c83bN98CvmQc9JVM5ZByT2CpfoxarCtbFB85uSCQZCjWZCsZAk2jO4dzZAUZC4cTfkBzTvaieIusKyrNJ2e9PrQ0MjZAdrcGA1XNEUS9aNyMC4Gen5gZDZD';
    $link = 'http://45.55.22.181/auction/bid/item/8';
    $group_id = '569806093039878';
    $url = 'https://graph.facebook.com/v2.6/'.$group_id.'/feed?';
	$data =  'access_token='.$token.'&message='.$message.'&link='.$link;
//echo $url;
//echo $myvars;

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);*/

	//return dd($message);

    return redirect()->route('get.auction.item.show',['auction' =>  $auction,'id' =>  $item->id ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShowItem($auction,$id)
    {
        $type = 0;
        if($auction=='seek')
            $type = 1;
        $item = $this->items->whereId($id)->where('type',$type)->first();

        //find通知
        if(Auth::check()){
            $notifications = Auth::user()->notifications()->where('notificatable_id', $id)->where('notificatable_type','App\Item')->where('is_read',0)->lists('id')->toArray();
            $reads = $this->notifications->whereIn('id',$notifications)->get();

            foreach ($reads as $key => $read) {
                $read->is_read = 1;
                $read->save();
            }
        }
        
        if(!$item){
            $item = null;
            return View('errors.item',compact('item'));  
        }

        $images = $item->albums()->first()->images()->get();
        $owner = $item->user()->first();
        $now = Carbon::now();

        $item_user = $item->users()
            ->orderBy('item_user.price', 'desc')
            ->get();
        //競投
        if($auction=='seek')
            $item_user = $item->users()
            ->orderBy('item_user.price', 'asc')
            ->get();

        //點閱率
        $item->hitpoint = $item->hitpoint+1;
        $item->save();
        

        if(Auth::check())
             return View('auctions.items.show', compact('item', 'images', 'owner', 'item_user','now','auction'));  
        else
             return View('auctions.items.sshow', compact('item', 'images', 'owner', 'item_user','now','auction'));  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEditItem($auction,$id)
    {
        $type = 0;
        if($auction=='seek')
            $type = 1;
        $item = $this->items->whereId($id)->where('type',$type)->first();
        //$item = $this->items->find($id);
        if($item->user()->first()->id == Auth::id()){
            $type = 'bid_items';
            //競投
            if($auction=='seek')
                $type = 'seek_items';

            $category = Category::all()->where('type',$type)->lists('name', 'id');
            return View('auctions.items.edit', compact('item','category','auction'));
        }
        //return dd($item->user()->first()->id);
        //return View('users.profile', compact('profile'), compact('majors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patchUpdateItem(ItemRequest $request,$id)
    {
        
        $item = $this->items->find($id);
        $item_type = $item->type;

        //check category_id
        $type = 'bid_items';
        if($item_type==1)
            $type = 'seek_items';
        $category = Category::where('type',$type)->where('id',$request->category_id);
        if($category->first()==null)
            return redirect()->back()->withErrors('無此分類');

        if($item->users()->count()!=0 || $item->free==1)
            $item = $this->items->update($request->except('price','free','edit','auction','image','_method', '_token'), $id);
        else
            $item = $this->items->update($request->except('free','edit','auction','image','_method', '_token'), $id);

        return redirect()->route('get.auction.item.show', ['auction' =>  $item_type==0 ? 'bid' : 'seek','id' =>  $id ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyItem($id)
    {

        $images = $this->items->find($id)->albums->first()->images()->get();
        //return $images->first()->file_path;
        foreach ($images as $image) {
            $this->images->deleteImageFile($image->toArray(), 'images/auctions');
        }
        //return dd($images);
        $item_type = $this->items->find($id)->type;
        $album = $this->items->find($id)->albums->first()->delete();
        $item = $this->items->delete($id);
        return redirect()->route('get.auction.index',['college' =>  Auth::check() ? Auth::user()->college()->first()->acronym : 'fju','auction' => $item_type==0 ? 'bid':'seek','type' => 'all']);
    }

    public function getAdmin($auction)
    {
        $now = Carbon::now();
        $user = $this->users->find(Auth::id());
        //拍賣or競投
        $type = 0;
        if($auction=='seek')
            $type = 1;

        $items = $user->item()->where('type',$type)->orderBy('end_time','desc')->paginate(10);
        $allitems = $user->item()->get();

        //find通知
        $notifications = Auth::user()->notifications()->where('link', 'like', '%'.'admin')->where('notificatable_type','App\Item')->where('is_read',0)->lists('id')->toArray();
        $reads = $this->notifications->whereIn('id',$notifications)->get();

        foreach ($reads as $key => $read) {
                $read->is_read = 1;
                $read->save();
        }
        

        return View('auctions.items.admin', compact('items','auction','now','allitems'));
    }

    public function postUploadItemImage(Request $request, $id)
    {
        $item = $this->items->find($id);
        $album = $item->albums()->first();

        
        $image_num = count($request->image);
        $image_db_num = count($item->albums()->first()->images()->get());
        $total_image_num = $image_num + $image_db_num;

        if($total_image_num > 3) {
            return redirect()->back()->withErrors('不能上傳超過3張圖片！');
        }

        $rules = [];
        $nbr = count($request->image) - 1;
        foreach(range(0, $nbr) as $index) {
            $messages = array(
            'image.' . $index.'.required'    => '請上傳圖片',
            'image.0.max'    => '圖片1大小不能超過10mb',
            'image.1.max'    => '圖片2大小不能超過10mb',
            'image.2.max'    => '圖片3大小不能超過10mb',
            'image.0.image'    => '檔案1只能為圖片',
            'image.1.image'    => '檔案2只能為圖片',
            'image.2.image'    => '檔案3只能為圖片',
             );

            $rules['image.' . $index] = 'required|max:10240|image';
        }
        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator);
        }

        //return dd($total_image_num);


        $images = $this->images->uploadImage($request->all(), Auth::id(), 'images/auctions'); 
        foreach($images as $image) {
        $image = $album->images()->create([
            'file_name' => $image->basename,
            'file_mime' => $image->mime,
            'file_path'  => '/images/auctions/' . $image->basename,
            'description' => $image->basename
        ]);
        }

        return redirect()->route('get.auction.item.edit', ['auction' =>  $item->type==0 ? 'bid' : 'seek','id' =>  $id ]);
    }

    public function destroyImage(Request $request,$id)
    {
        //$album = $this->items->find($id)->albums->first()->delete();
        $image = $this->images->find($id)->album()->first()->images()->get();
        //return dd($image);
        //return $this->images->deleteImageFile();
        if($request->auction=='bid'){
            if(count($image) > 1){
                $this->images->deleteImageFile($image->find($id)->toArray(), 'images/auctions');
                $item = $this->images->delete($id);
                return redirect()->back();
            }
            
            return redirect()->back()->withErrors('至少要存在一張圖片');
        }else{
            $this->images->deleteImageFile($image->find($id)->toArray(), 'images/auctions');
            $item = $this->images->delete($id);
            return redirect()->back();
        }
    }

    public function postBidItem(Request $request, $id)
    {
        $item = $this->items->find($id);

        if($item->type==0){
            //拍賣
            $item_user = $item->users()
                ->orderBy('item_user.price', 'desc')
                ->get();
            if(count($item->users()->get()) == 0){
                $item_min = $item->price;
            }else{
                $item_min = $item_user->first()->pivot->price + 1;
            }

            $messages = array(
            'price.required'    => '請輸入價錢',
            'price.integer'    => '價錢只能是整數',
            'price.min'    => '價錢不能小於'. $item_min,
             );
           
            //$max_price
            $validator = Validator::make($request->all(), [
                'price' => 'required|integer|min:'. $item_min,
            ],$messages);
        }else{
            //競投
            $item_user = $item->users()
                ->orderBy('item_user.price', 'asc')
                ->get();
            if(count($item->users()->get()) == 0){
                $item_max = $item->price;
            }else{
                $item_max = $item_user->first()->pivot->price - 1;
            }

            $messages = array(
            'price.required'    => '請輸入價錢',
            'price.integer'    => '價錢只能是整數',
            'price.min'    => '價錢不能為負數',
            'price.max'    => '價錢不能大於'. $item_max,
             );
           
            //$max_price
            $validator = Validator::make($request->all(), [
                'price' => 'required|integer|min:0|max:'. $item_max,
            ],$messages);

        }

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
            //return response()->json(['status' => 'error' ,'message' => $validator->messages()], 400);
        }

        $item->users()->attach(Auth::id(), ['price' => $request->price]);





        //$now = Carbon::now();
        //$end_time = Carbon::now()->addDays(7);
        $item->end_time = Carbon::now()->addDays(1);
        $item->save();
        $user = $this->users->find(Auth::id());


        $item_creater = $item->user()->get();
        $bidding_user = $item->users()->whereNotIn('user_id', [Auth::id()])->groupBy("user_id")->get();
        //return dd($bidding_user);
        //$user->items()->attach($id, ['price' => $request->price]);

        // $content_bidding =  $user->lastname . $user->firstname .' 出價 NT$'. $request->price .' 拍賣物品 ['. $item->name.']';
        // $content_creater =  $user->lastname . $user->firstname . ' 出價 NT$'. $request->price .' 拍賣你的物品 ['. $item->name.']';
        if($item->type == 0){
            $content_bidding =  ' 出價 NT$'. $request->price .' 拍賣項目 「'. $item->name.'」';
            $content_creater =  ' 出價 NT$'. $request->price .' 拍賣你的項目 「'. $item->name.'」';
            $link = '/auction/bid/item/'. $item->id;       
        }else{
            $content_bidding =  ' 出價 NT$'. $request->price .' 競投項目 「'. $item->name.'」';
            $content_creater =  ' 出價 NT$'. $request->price .' 競投你的項目 「'. $item->name.'」'; 
            $link = '/auction/seek/item/'. $item->id; 
        }



	    // $item->notifications()->create(['user_id' => $item->user()->first()->id, 'content' => $content]);
        $this->notifications->postNotificationToUsers($item_creater, $content_creater, $link, $item->id ,'App\Item');
        $this->notifications->postNotificationToUsers($bidding_user, $content_bidding, $link, $item->id ,'App\Item');

        //$this->notifications->postNotificationToUsers(User::all(), $content, $item->id ,'App\Item');
        //$this->notifications->postToFacebookGroup(

	return redirect()->back();
    }

    // For Testing
    public function getAdminAPI()
    {
        $user = $this->users->find(Auth::id());
        $item = $this->items->find(4);
        return dd($item->user()->associate($user)->save());

        return \Response::json([
            'data' => $items
            ],200);
        return View('auctions.items.admin', compact('items'));
    }


    public function postComment(CommentRequest $request, $id)
    {
        $user = $this->users->find(Auth::id());

        $item = $this->items->find($id);



        $comment = $item->comments()->create(['user_id' => Auth::id(), 'content' => $request->content]);

        $item_creater = $item->user()->get();
        
        //return dd($item_creater->id);
        //$this->notifications->postNotificationToUsers($item_creater, $content_creater, $item->id ,'App\Item');
        if($item_creater->first()->id != $user->id){
        $content_creater = ' 在你的項目 「'. $item->name.'」 留言';

        if($item->type == 0){
            $link = '/auction/bid/item/'. $item->id;  
        }else{
            $link = '/auction/seek/item/'. $item->id; 
        }


        $this->notifications->postNotificationToUsers($item_creater, $content_creater, $link, $item->id ,'App\Item');
        }
        return redirect()->back();
    }

    public function postReport(Request $request, $id)
    {
        
        $item = $this->items->find($id);

        if($item->reports()->where('user_id',Auth::id())->count()!=0)
            return redirect()->back()->withErrors('請勿重複舉報同一項目！');
       
        $report = $item->reports()->create(['user_id' => Auth::id(), 'content' => $request->content]);
        
        //舉報三次自動disable
        if($item->reports()->count()>=3){
            $item->disabled=1;
            $item->save();
        }


        return redirect()->back();
    }

    public function postRepost(Request $request, $id)
    {
        $item = $this->items->find($id);
        $start_time = Carbon::now();
        $end_time = Carbon::now()->addDays(30);

        $item->start_time = $start_time;
        $item->end_time = $end_time;
        $item->repost = $item->repost+1;
        $item->notification = 0;
        $item->save();

        return redirect()->back();
    }

    public function postFreeItem(Request $request, $id)
    {
        $item = $this->items->find($id);

        $item->users()->attach(Auth::id(), ['price' => 0]);
        $item->end_time = Carbon::now();
        $item->save();

        $item_creater = $item->user()->get();
        $content_creater =  ' 得到你的贈品 「'. $item->name.'」';
        $link = '/auction/bid/item/'. $item->id;  
        $this->notifications->postNotificationToUsers($item_creater, $content_creater, $link, $item->id ,'App\Item');
        
        return redirect()->back();
    }




}
