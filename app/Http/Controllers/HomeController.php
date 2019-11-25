<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Major;
use Carbon\Carbon;
use Repositories\Contracts\NotificationRepositoryInterface;
use Repositories\Contracts\ItemRepositoryInterface;
use Repositories\Contracts\UserRepositoryInterface;
use Repositories\Contracts\ImageRepositoryInterface;

use Cloudder;

class HomeController extends Controller
{


    protected $images;
    public function __construct(UserRepositoryInterface $users, NotificationRepositoryInterface $notifications,ItemRepositoryInterface $items, ImageRepositoryInterface $images){
        $this->items = $items;
        $this->users = $users;
        $this->notifications = $notifications;
	$this->images = $images;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $majors = Major::where('id', '>', 1)->orderBy('id')->lists('name', 'id');
        $items = $this->items->where('disabled','0')->where('type','0')->where('end_time','>',Carbon::now())->orderby('created_at','desc')->get()->take(10);

         if(!Auth::check())
             return View('welcome',compact('majors','items'));  
        else
             return redirect()->route('get.auction.index',['college' =>  Auth::user()->college()->first()->acronym ,'auction' => 'bid','type' => 'all']);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getItemPageScroll(Request $request){

        $items_per_page = 2;

        $items = \App\Item::paginate($items_per_page);
        //return dd($request->referrer());
        if($request->ajax()) {
            return [
                'items' => view('test.ajax.index')->with(compact('items'))->render(),
                'next_page' => $items->nextPageUrl(),
                'current_page' => $items->url($items->currentPage()),
            ];
        }
 
        return view('test.index')->with(compact('items'));


    }



    public function getItemPage(){

    // $items = \App\Item::where('end_time','<' ,Carbon::now())->where('notification', 0)->get();
    //     return dd($items);
        //return View('items', compact($page_data));
                $items = \App\Item::where('end_time','>' ,Carbon::now())->where('free', 0)->where('notification', 1)->get();
                foreach($items as $item){
         //$item_winner = $this->users->find($item->users()->get()->pivot->user_id);
                    $item_winner = $item->users()->where('pivot_user_id',2);
     }
         return dd($item_winner->get());
        return dd(\App\Notification::all());
        return dd($this->notifications->fetch());
    }


   public function postUpload(Request $request){
	//$images = $this->images->uploadImage($request->all(), 1, 'images/auctions');
	//$image = $request;
	//return dd($request->all());
	//Cloudder::upload($filename, $publicId, array $options, array $tags);
	//Cloudder::upload($filename, $publicId, array $options, array $tags);
	$filename = $request->file('file')->getRealPath();
	//return dd($filename);
	Cloudder::upload($filename, null);
	$fileUrl = Cloudder::secureShow(Cloudder::getPublicId(), ["width" => 800, "height" => 600]);
	//$fileUrl = Cloudder::showPrivateUrl(Cloudder::getPublicId(), "*", ["width" => 800, "height" => 600])
        return $fileUrl;
   }



}
