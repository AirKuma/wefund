<?php

namespace App\Http\Controllers\API\Auction;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator;
use Carbon\Carbon;
use Repositories\Contracts\ItemRepositoryInterface;

use Dingo\Api\Routing\Helpers;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Illuminate\Support\Collection;

use App\Transformers\Auction\ItemIndexTransformers;
use App\Transformers\Auction\ItemShowTransformers;
use App\Transformers\Comment\CommentTransformers;

use App\Http\Requests\Comment\CommentRequest;
use Repositories\Contracts\NotificationRepositoryInterface;
use App\Category;
use App\Transformers\Category\CategoryTransformers;
use App\Transformers\Auction\ItemBidTransformers;
use App\College;
use Repositories\Contracts\ImageRepositoryInterface;
//use App\Item;
use App\Http\Requests\Auction\API\ItemRequest;
//use App\Http\Requests\Image\ImageRequest;
use Repositories\Contracts\FavorRepositoryInterface;
use App\Transformers\Image\ImageTransformers;

use Cloudder;

class ItemController extends Controller
{
    use Helpers;
    protected $items;
    protected $images;
    protected $notifications;
    protected $favors;

    public function __construct(ItemRepositoryInterface $items,NotificationRepositoryInterface $notifications, ImageRepositoryInterface $images, FavorRepositoryInterface $favors)
    {
        $this->middleware('apiauth', ['except' => ['getHomeItem','getIndex','show','getComment','getCategoey','getItemBid','getIfFavor','getItemAdminCount','getItemCategoryCount','getCategogyIDCount','getImage']]);
        //$this->middleware('cors');
	    $this->items = $items;
        $this->notifications = $notifications;
        $this->images = $images;
        $this->favors = $favors;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHomeItem()
    {
        $items = $this->items->where('disabled','0')->where('type','0')->where('end_time','>',Carbon::now())->orderby('created_at','desc')->get()->take(10);
        return $this->response->collection($items, new ItemIndexTransformers())->setStatusCode(200);
    }

    public function getIndex(Request $request)
    {
        $type = $request->type;
        $college = $request->college;
        $category = $request->category;
        $limit = $request->limit;
        $offset = $request->offset;
        $fields = $request->fields;

        if(is_numeric($college))
            $itemcollege = College::where('id',$college);
        else
            $itemcollege = College::where('acronym',$college);

        $itemtype = 0;
        if($type=='seek'){
            $itemtype = 1;
        }

        $items = $itemcollege->first()->items()->where('disabled',0)->where('type',$itemtype)->where('end_time','>',Carbon::now());

        //指派性別
        $user = app('Dingo\Api\Auth\Auth')->user();
        if($user){
            if($user->gender==1)
                $items = $items->whereIn('target',[0,1]);
            else
                $items = $items->whereIn('target',[0,2]);
        }else
             $items = $items->whereIn('target',[0,1,2]);

        //分類搜尋
        if($category!=null && $category!="free" && $category!="my"){
            if(!is_numeric($category)){
                if($type=='bid')
                $category =  Category::where('type','bid_items')->where('en_name',$category)->first()->id;
                else
                    $category =  Category::where('type','seek_items')->where('en_name',$category)->first()->id;
            }
            $items = $items->where('category_id',$category);
        }
        elseif($category=='free')
            $items = $items->where('free','1');
        elseif($category=='my')
            $items = $user->items()->where('type',$itemtype)->where('disabled',0)->where('end_time','>',Carbon::now())->groupBy('items.id');

        //無線下拉&更新
        if($limit != null && $offset != null)
            $items = $items->orderBy('end_time','asc')->limit($limit)->offset($offset);
        // elseif($before != null)
        //     $items = $items->where('end_time', '<', $before);

        $items = $items->get();

        return $this->response->collection($items, new ItemIndexTransformers())->setStatusCode(200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreateItem(ItemRequest $request)
    {

        // $var = '';
        // $images = $request->images;
        // foreach($images as $image) {
        //     $var = $image['id'];
        // }

        // return  $var;
        $auction = $request->Itemtype;

        //check category_id
        $type = 'bid_items';
        if($auction=='seek')
            $type = 'seek_items';
        $category = Category::where('type',$type)->where('id',$request->category_id);
        if($category->first()==null)
            return $this->response->errorBadRequest();
            //return redirect()->back()->withErrors('無此分類');

        //Validator for checking rhat limit 3 file upload
        $start_time = Carbon::now();
        $end_time = Carbon::now()->addDays(30);
        //return dd($start_time);

        $image_num = count($request->image);
        if($image_num > 3) {
            //return redirect()->back()->withInput()->withErrors('不能上傳超過三張圖片！');
            return $this->response->errorBadRequest();
        }
        if($auction=='bid' && $image_num==0)
            return $this->response->errorBadRequest();
        // $rules = [];
        // $nbr = count($request->image) - 1;
        // foreach(range(0, $nbr) as $index) {
        //     $messages = array(
        //     'image.' . $index.'.required'    => '請上傳圖片',
        //     'image.0.max'    => '圖片1大小不能超過10mb',
        //     'image.1.max'    => '圖片2大小不能超過10mb',
        //     'image.2.max'    => '圖片3大小不能超過10mb',
        //     'image.0.image'    => '檔案1只能為圖片',
        //     'image.1.image'    => '檔案2只能為圖片',
        //     'image.2.image'    => '檔案3只能為圖片',
        //      );

        //     if($auction=='bid')
        //         $rules['image.' . $index] = 'required|max:10240|image';
        //     else
        //         $rules['image.' . $index] = 'max:10240|image';
        // }
        // $validator = Validator::make($request->all(), $rules,$messages);

        // if ($validator->fails()) {
        //     // return redirect()->back()
        //     //         ->withErrors($validator)
        //     //         ->withInput();
        //     return $this->response->errorBadRequest();
        // }


        //find a user 
        $user = app('Dingo\Api\Auth\Auth')->user();
        // create a item
        $item = $user->item()->create($request->all());
        $item->start_time = $start_time;
        $item->end_time = $end_time;
        if($auction=='seek')
            $item->type = 1;
        $item->save();
        // create a album of item.
        $album = $item->albums()->create(['user_id' => $user->id, 'name' => $item->name]);
        // upload file and return file path
        //$images = $this->images->uploadImage($request->all(), $user->id, 'images/auctions'); 
        
        // crate a images of album
        $images = $request->image;
        if($images!=null){
            foreach($images as $image) {
            $image = $album->images()->create([
                'file_name' => $image['id'],
                'file_mime' => '',
                'file_path'  => $image['url'],
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
        //$item_url = route('get.auction.item.show', ['auction' => $auction,'id' => $item->id]);
        $item_url =  'https://www.loyaus.com/auction/'.$auction.'/item/'.$item->id;

        $notification = $this->notifications->postToFacebookGroup($message,'EAAEPz1VMu8sBAGvggsZBd1CNLuKQShOQJ6UUdFwmTUpRVV0sWmZCo3Q32RU3c83bN98CvmQc9JVM5ZByT2CpfoxarCtbFB85uSCQZCjWZCsZAk2jO4dzZAUZC4cTfkBzTvaieIusKyrNJ2e9PrQ0MjZAdrcGA1XNEUS9aNyMC4Gen5gZDZD',$item_url,$user->college()->first()->group_id);

        return $this->response->array($item)->setStatusCode(200);
    }

    public function postCreateItemWeb(ItemRequest $request)
    {

        // $var = '';
        // $images = $request->image;
        // foreach($images as $image) {
        //     $var = $image;
        // }

        // return  $var;
        $auction = $request->Itemtype;

        //check category_id
        $type = 'bid_items';
        if($auction=='seek')
            $type = 'seek_items';
        $category = Category::where('type',$type)->where('id',$request->category_id);
        if($category->first()==null)
            return $this->response->errorBadRequest();
            //return redirect()->back()->withErrors('無此分類');

        //Validator for checking rhat limit 3 file upload
        $start_time = Carbon::now();
        $end_time = Carbon::now()->addDays(30);
        //return dd($start_time);

        $image_num = count($request->image);
        if($image_num > 3) {
            //return redirect()->back()->withInput()->withErrors('不能上傳超過三張圖片！');
            return $this->response->errorBadRequest();
        }
        if($auction=='bid' && $image_num==0)
            return $this->response->errorBadRequest();
        
        $rules = [];
        $nbr = count($request->image) - 1;
        foreach(range(0, $nbr) as $index) {
            $messages = array(
            'image.' . $index.'.required'    => '請上傳圖片',
            'image.0.image'    => '檔案1只能為圖片',
            'image.1.image'    => '檔案2只能為圖片',
            'image.2.image'    => '檔案3只能為圖片',
             );

            if($auction=='bid')
                $rules['image.' . $index] = 'required|image';
            else
                $rules['image.' . $index] = 'image';
        }
        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            // return redirect()->back()
            //         ->withErrors($validator)
            //         ->withInput();
            return $this->response->errorBadRequest();
        }


        //find a user 
        $user = app('Dingo\Api\Auth\Auth')->user();
        // create a item
        $item = $user->item()->create($request->all());
        $item->start_time = $start_time;
        $item->end_time = $end_time;
        if($auction=='seek')
            $item->type = 1;
        $item->save();
        // create a album of item.
        $album = $item->albums()->create(['user_id' => $user->id, 'name' => $item->name]);
        // upload file and return file path
        //$images = $this->images->uploadImage($request->all(), $user->id, 'images/auctions'); 
        
        // crate a images of album
        $images = $request->image;
        if($images!=null){
            foreach($images as $image) {
            Cloudder::upload($image->getRealPath(), null, [], ['item']);
            $image = $album->images()->create([
                'file_name' => Cloudder::getResult()['public_id'],
                'file_mime' => '',
                'file_path'  => Cloudder::getResult()['url'],
                'description' => $item->description,
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
        //$item_url = route('get.auction.item.show', ['auction' => $auction,'id' => $item->id]);
        $item_url =  'https://www.loyaus.com/auction/'.$auction.'/item/'.$item->id;
        
        $notification = $this->notifications->postToFacebookGroup($message,'EAAEPz1VMu8sBAGvggsZBd1CNLuKQShOQJ6UUdFwmTUpRVV0sWmZCo3Q32RU3c83bN98CvmQc9JVM5ZByT2CpfoxarCtbFB85uSCQZCjWZCsZAk2jO4dzZAUZC4cTfkBzTvaieIusKyrNJ2e9PrQ0MjZAdrcGA1XNEUS9aNyMC4Gen5gZDZD',$item_url,$user->college()->first()->group_id);

        return $this->response->array($item)->setStatusCode(200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = $this->items->where('id',$id)->get();
        //return $item;


        if($item=='[]'){
            //$item = null;
            //return $this->response->array($item);
            return $this->response->errorBadRequest();
        }
        else{
            //find通知
            $user = app('Dingo\Api\Auth\Auth')->user();
            if($user){
                $notifications = $user->notifications()->where('notificatable_id', $id)->where('notificatable_type','App\Item')->where('is_read',0)->lists('id')->toArray();
                $reads = $this->notifications->whereIn('id',$notifications)->get();

                foreach ($reads as $key => $read) {
                    $read->is_read = 1;
                    $read->save();
                }
            }
            //點閱率
            $itemthis = $this->items->find($id);
            $itemthis->hitpoint = $itemthis->hitpoint+1;
            $itemthis->save();

           return $this->response->collection($item, new ItemShowTransformers())->setStatusCode(200);
        }
         
    }

    public function putUpdateItem(ItemRequest $request,$id)
    {
        
        $item = $this->items->find($id);

        $user = app('Dingo\Api\Auth\Auth')->user();
        if($item->user_id != $user->id && $user->permission != 1)
            return $this->response->errorBadRequest();

        $item_type = $item->type;

        //check category_id
        $type = 'bid_items';
        if($item_type==1)
            $type = 'seek_items';
        $category = Category::where('type',$type)->where('id',$request->category_id);
        if($category->first()==null)
            return $this->response->errorBadRequest();
            //return redirect()->back()->withErrors('無此分類');

        if($item->users()->count()!=0 || $item->free==1)
            $item = $this->items->update($request->except('price','free','edit','auction','image'), $id);
        else
            $item = $this->items->update($request->except('free','edit','auction','image'), $id);

        return $this->response->array($item)->setStatusCode(200);
    }

    public function getImage(Request $request,$id)
    {
        $item = $this->items->find($id);

        // if(!$items)
        //     return $this->response->errorBadRequest();

        $images = $item->albums->first()->images;
       

        return $this->response->collection($images, new ImageTransformers())->setStatusCode(200);
    }

    public function postUploadimage(Request $request){

        $rules['file'] = 'required|image';
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->response->errorBadRequest();
        }


        $filename = $request->file('file')->getRealPath();

        Cloudder::upload($filename, null, [], ['item']);

       // $imageID = Cloudder::getPublicId();
        //$fileUrl = Cloudder::secureShow($imageID, ["width" => 800, "height" => 600]);

        $surl = Cloudder::show(Cloudder::getResult()['public_id'], ["width"=>200, "height"=>200, "crop"=>"limit"]);
        //return Cloudder::getResult();
        return response()->json(['id' => Cloudder::getResult()['public_id'], 'url' => Cloudder::getResult()['url'], 'surl' => $surl]);
   }
    public function postUploadItemImageAPP(Request $request,$id){

        $rules['file'] = 'required|image';
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->response->errorBadRequest();
        }
        
        $item = $this->items->find($id);

        $image_db_num = count($item->albums()->first()->images()->get());
        if($image_db_num >= 3) {
            return $this->response->errorBadRequest();
            //return redirect()->back()->withErrors('不能上傳超過3張圖片！');
        }

        $user = app('Dingo\Api\Auth\Auth')->user();
        if($item->user_id != $user->id && $user->permission != 1)
            return $this->response->errorBadRequest();

        $filename = $request->file('file')->getRealPath();
            
        Cloudder::upload($filename, null, [], ['item']);

        $album = $item->albums()->first();

        $image = $album->images()->create([
            'file_name' => Cloudder::getResult()['public_id'],
            'file_mime' => '',
            'file_path'  => Cloudder::getResult()['url'],
            'description' => $item->description
        ]);

        return $this->response->array($image)->setStatusCode(200);
    }

    public function postUploadItemImage(Request $request,$id)
    {
        //$id = $request->itemid;

        $item = $this->items->find($id);

        $user = app('Dingo\Api\Auth\Auth')->user();
        if($item->user_id != $user->id && $user->permission != 1)
            return $this->response->errorBadRequest();

        $album = $item->albums()->first();

        //return $request->all();
        $image_num = count($request->image);
        $image_db_num = count($item->albums()->first()->images()->get());
        $total_image_num = $image_num + $image_db_num;

        if($total_image_num > 3) {
            return $this->response->errorBadRequest();
            //return redirect()->back()->withErrors('不能上傳超過3張圖片！');
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
            return $this->response->errorBadRequest();
            // return redirect()->back()
            //         ->withErrors($validator);
        }


        $images = $this->images->uploadImage($request->all(), Auth::id(), 'images/auctions'); 
        foreach($images as $image) {
        $image = $album->images()->create([
            'file_name' => $image->basename,
            'file_mime' => $image->mime,
            'file_path'  => '/images/auctions/' . $image->basename,
            'description' => $image->basename
        ]);
        }

        return $this->response->array($item)->setStatusCode(200);
    }

    public function destroyImage(Request $request,$id)
    {
        $auction = $request->auction;
        //$album = $this->items->find($id)->albums->first()->delete();
        $image = $this->images->find($id)->album()->first()->images()->get();

        $dimage=$this->images->find($id);
        $user = app('Dingo\Api\Auth\Auth')->user();
        $user_image = $dimage->album()->first()->item2()->where('user_id',$user->id);
        if($user_image->first()==null && $user->permission != 1)
            return $this->response->errorBadRequest();
        //return dd($image);
        //return $this->images->deleteImageFile();

        if($auction==0){
            if(count($image) > 1){
                $this->images->deleteImageFile($image->find($id)->toArray(), 'images/auctions');
                $item = $this->images->delete($id);
                //return redirect()->back();
                $publicId = $request->publicid;
                Cloudder::delete($publicId, null);

                return $this->response->array($item)->setStatusCode(200);
            }
            //return redirect()->back()->withErrors('至少要存在一張圖片');
            return $this->response->errorBadRequest();
        }else{
            $this->images->deleteImageFile($image->find($id)->toArray(), 'images/auctions');
            $item = $this->images->delete($id);
            //return redirect()->back();
            $publicId = $request->publicid;

            Cloudder::delete($publicId, null);
            return $this->response->array($item)->setStatusCode(200);
        }
    }

    public function destroyItem($id)
    {

        $ditem = $this->items->find($id);
        $user = app('Dingo\Api\Auth\Auth')->user();
        if($ditem->user_id != $user->id && $user->permission != 1)
            return $this->response->errorBadRequest();

        $images = $this->items->find($id)->albums->first()->images()->get();
        foreach ($images as $image) {
            $this->images->deleteImageFile($image->toArray(), 'images/auctions');
            Cloudder::delete($image->file_name, null);
        }

        $item_type = $this->items->find($id)->type;
        $album = $this->items->find($id)->albums->first()->delete();
        $item = $this->items->delete($id);

        return $this->response->array($item)->setStatusCode(200);
    }

    public function getComment(Request $request,$id)
    {
        $limit = $request->limit;
        $offset = $request->offset;

        $items = $this->items->find($id);

        // if(!$items)
        //     return $this->response->errorBadRequest();

        $comments = $items->comments()->orderBy('created_at','desc');
       
       if($limit != null && $offset != null)
            $comments = $comments->limit($limit)->offset($offset);

        $comments = $comments->get();

        return $this->response->collection($comments, new CommentTransformers())->setStatusCode(200);
    }

    public function postComment(CommentRequest $request,$id)
    {
        $user = app('Dingo\Api\Auth\Auth')->user();

        // return response()->json($user);
        // return $this->response->array($user)->setStatusCode(200);
        // return dd($user);
        $item = $this->items->find($id);

        $comment = $item->comments()->create(['user_id' => $user->id, 'content' => $request->content]);

        $item_creater = $item->user()->get();

        if($item_creater->first()->id != $user->id){
        $content_creater = ' 在你的項目 「'. $item->name.'」 留言';

        if($item->type == 0){
            $link = '/auction/bid/item/'. $item->id;  
        }else{
            $link = '/auction/seek/item/'. $item->id; 
        }


        $this->notifications->postNotificationToUsersAPI($item_creater, $content_creater, $link, $item->id ,'App\Item');
        }
        return $this->response->array($user)->setStatusCode(200);
    }

    public function getCategoey($type)
    {
        $categories = Category::where('type','bid_items')->get();
        if($type=='seek')
            $categories = Category::where('type','seek_items')->get();
        
        return $this->response->collection($categories, new CategoryTransformers())->setStatusCode(200);
    }

    public function getItemBid(Request $request,$id)
    {
       $limit = $request->limit;
       $offset = $request->offset;

       //return $limit;

       $item = $this->items->find($id);
       $item_user = $item->users()->orderBy('item_user.price', 'desc');
        //競投
        if($item->type==1)
            $item_user = $item->users()->orderBy('item_user.price', 'asc');

        if($limit != null && $offset != null)
            $item_user = $item_user->limit($limit)->offset($offset);
        
        $item_user = $item_user->get();

        return $this->response->collection($item_user, new ItemBidTransformers())->setStatusCode(200);
    }

    public function postBid(Request $request, $id)
    {
        $user = app('Dingo\Api\Auth\Auth')->user();

        $item = $this->items->find($id);

        if($user->id==$item->user_id || $item->end_time <= Carbon::now())
            return $this->response->errorBadRequest();


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
            // //return redirect()->back()
            //             ->withErrors($validator)
            //             ->withInput();
            return response()->json(['status' => 'error' ,'message' => $validator->messages()], 400);
        }

        $item->users()->attach($user->id, ['price' => $request->price]);

        $item->end_time = Carbon::now()->addDays(1);
        $item->save();

        $item_creater = $item->user()->get();
        $bidding_user = $item->users()->whereNotIn('user_id', [$user->id])->groupBy("user_id")->get();

        if($item->type == 0){
            $content_bidding =  ' 出價 NT$'. $request->price .' 拍賣項目 「'. $item->name.'」';
            $content_creater =  ' 出價 NT$'. $request->price .' 拍賣你的項目 「'. $item->name.'」';
            $link = '/auction/bid/item/'. $item->id;       
        }else{
            $content_bidding =  ' 出價 NT$'. $request->price .' 競投項目 「'. $item->name.'」';
            $content_creater =  ' 出價 NT$'. $request->price .' 競投你的項目 「'. $item->name.'」'; 
            $link = '/auction/seek/item/'. $item->id; 
        }

        $this->notifications->postNotificationToUsersAPI($item_creater, $content_creater, $link, $item->id ,'App\Item');
        $this->notifications->postNotificationToUsersAPI($bidding_user, $content_bidding, $link, $item->id ,'App\Item');

        return $this->response->array($bidding_user)->setStatusCode(200);
    }

    public function postFree(Request $request, $id)
    {
        $user = app('Dingo\Api\Auth\Auth')->user();
        $item = $this->items->find($id);

        if($user->id==$item->user_id || $item->end_time <= Carbon::now())
            return $this->response->errorBadRequest();

        $item->users()->attach($user->id, ['price' => 0]);
        $item->end_time = Carbon::now();
        $item->save();

        $item_creater = $item->user()->get();
        $content_creater =  ' 得到你的贈品 「'. $item->name.'」';
        $link = '/auction/bid/item/'. $item->id;  
        $this->notifications->postNotificationToUsersAPI($item_creater, $content_creater, $link, $item->id ,'App\Item');
        
        return $this->response->array($item)->setStatusCode(200);
    }

    public function postReport(Request $request, $id)
    {
        $user = app('Dingo\Api\Auth\Auth')->user();
        $item = $this->items->find($id);

        if($item->reports()->where('user_id',$user->id)->count()!=0)
            return redirect()->back()->withErrors('請勿重複舉報同一項目！');
       
        $report = $item->reports()->create(['user_id' => $user->id, 'content' => $request->content]);
        
        //舉報三次自動disable
        if($item->reports()->count()>=3){
            $item->disabled=1;
            $item->save();
        }


        return $this->response->array($report)->setStatusCode(200);
    }

    public function getMyItem(Request $request)
    {
        $limit = $request->limit;
        $offset = $request->offset;

        $user = app('Dingo\Api\Auth\Auth')->user();
        $items = $user->item()->orderBy('end_time','desc')->limit($limit)->offset($offset)->get();

        return $this->response->collection($items, new ItemIndexTransformers())->setStatusCode(200);
    }

    public function getMyBidItem(Request $request)
    {
        $limit = $request->limit;
        $offset = $request->offset;

        $user = app('Dingo\Api\Auth\Auth')->user();
        $items = $user->items()->where('disabled',0)->groupBy('items.id')->orderBy('end_time','desc')->limit($limit)->offset($offset)->get();


        return $this->response->collection($items, new ItemIndexTransformers())->setStatusCode(200);
    }

    public function getItemAdmin(Request $request)
    {
        $auction = $request->type;
        // $limit = $request->limit;
        // $offset = $request->offset;

        $user = app('Dingo\Api\Auth\Auth')->user();
        //拍賣or競投
        $type = 0;
        if($auction=='seek')
            $type = 1;

        $items = $user->item()->where('type',$type)->orderBy('end_time','desc')->get();

        //find通知
        $notifications = $user->notifications()->where('link', 'like', '%'.'admin')->where('notificatable_type','App\Item')->where('is_read',0)->lists('id')->toArray();
        $reads = $this->notifications->whereIn('id',$notifications)->get();

        foreach ($reads as $key => $read) {
                $read->is_read = 1;
                $read->save();
        }

        return $this->response->collection($items, new ItemIndexTransformers())->setStatusCode(200);
    }

    public function postRepost($id)
    {
        $item = $this->items->find($id);

        $user = app('Dingo\Api\Auth\Auth')->user();
        if($item->user_id != $user->id && $user->permission != 1)
            return $this->response->errorBadRequest();

        $start_time = Carbon::now();
        $end_time = Carbon::now()->addDays(30);

        $item->start_time = $start_time;
        $item->end_time = $end_time;
        $item->repost = $item->repost+1;
        $item->notification = 0;
        $item->save();

        return $this->response->array($item)->setStatusCode(200);
    }

    public function postFavor(Request $request,$id)
    {
        $item = $this->items->find($id);

        $user = app('Dingo\Api\Auth\Auth')->user();

        if($item->favors()->where('user_id',$user->id)->first()!=null)
            $favor = $this->favors->delete($item->favors()->where('user_id',$user->id)->first()->id);
        else
            $favor = $item->favors()->create(['user_id' => $user->id]);

        return $this->response->array($favor)->setStatusCode(200);
    }

    public function getIfFavor($id){

        $item = $this->items->find($id);
        
        if(!$item){
            return $this->response->errorBadRequest();
        }

        $user = app('Dingo\Api\Auth\Auth')->user();
        $favorcount = $item->favors()->where('user_id',$user->id)->count();

        return $favorcount;
    }

    public function getItemAdminCount(){

        $user = app('Dingo\Api\Auth\Auth')->user();

        $mybid = $user->item()->where('type',0)->count();

        $myseek = $user->item()->where('type',1)->count();

        $myitem = [$mybid,$myseek];

        return $this->response->array($myitem)->setStatusCode(200);
    }

    public function getItemCategoryCount(Request $request){
        //return "123";

        $type = $request->type;
        $college = $request->college;

        $itemcollege = College::where('acronym',$college);

        $itemtype = 0;
        if($type=='seek'){
            $itemtype = 1;
        }

        $items = $itemcollege->first()->items()->where('disabled',0)->where('type',$itemtype)->where('end_time','>',Carbon::now());

        //指派性別
        $user = app('Dingo\Api\Auth\Auth')->user();
        if($user){
            if($user->gender==1)
                $items = $items->whereIn('target',[0,1]);
            else
                $items = $items->whereIn('target',[0,2]);
        }else
             $items = $items->whereIn('target',[0,1,2]);



        $countarray = array();

        $allitem = $items->count();
        $myitem = $user->items()->where('type',$itemtype)->where('disabled',0)->where('end_time','>',Carbon::now())->groupBy('items.id')->get()->count();



        $categories = Category::where('type','bid_items')->get();
        if($type=='seek')
            $categories = Category::where('type','seek_items')->get();




        foreach($categories as $key => $category){
            $count = $this->getCategogyIDCount($type,$college,$category->id);
            //$count = $items->where('category_id',$category->id)->count();
            //$categorycount =  $categories->where('en_name',$category->en_name)->first();
            array_push($countarray, $count);
        }

                            //return $items->count();
        // $category =  Category::where('type','seek_items')->where('en_name','book')->first();

        // //$a = $items->where('category_id',1)->count();
        // $b = $items->where('category_id',2)->count();
        // $c = $category->count();
        
        // return $c;


        array_push($countarray,$allitem,$myitem);
        if($type=='bid'){
            $free = $items->where('free','1')->count();
            array_push($countarray,$free);
        }

        // //分類搜尋
        // if($category!=null && $category!="free" && $category!="my"){
        //     if(!is_numeric($category)){
        //         if($type=='bid')
        //         $category =  Category::where('type','bid_items')->where('en_name',$category)->first()->id;
        //         else
        //             $category =  Category::where('type','seek_items')->where('en_name',$category)->first()->id;
        //     }
        //     $items = $items->where('category_id',$category);
        // }
        // elseif($category=='free')
        //     $items = $items->where('free','1');
        // elseif($category=='my')
        //     $items = $user->items()->where('type',$itemtype)->where('disabled',0)->where('end_time','>',Carbon::now())->groupBy('items.id');

        return $this->response->array($countarray)->setStatusCode(200);
    }

    public function getCategogyIDCount($type,$college,$category){

        $type = $type;
        $college = $college;

        $itemcollege = College::where('acronym',$college);

        $itemtype = 0;
        if($type=='seek'){
            $itemtype = 1;
        }

        $items = $itemcollege->first()->items()->where('disabled',0)->where('type',$itemtype)->where('end_time','>',Carbon::now());

        //指派性別
        $user = app('Dingo\Api\Auth\Auth')->user();
        if($user){
            if($user->gender==1)
                $items = $items->whereIn('target',[0,1]);
            else
                $items = $items->whereIn('target',[0,2]);
        }else
             $items = $items->whereIn('target',[0,1,2]);

        return $items->where('category_id',$category)->count();

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

}
