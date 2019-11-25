<?php

namespace App\Http\Controllers\Notification;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->get();
        // return $notifications;

        // $user_all = \App\User::all();
        // $receiver = array();

        //1.user_arry 2.content 3.noti_id 4. type 

        // foreach ($user_all as $user) {
        //     array_push($receiver, 
        //         array('user_id'=> $user->id, 'notificatable_id' => 1 , 'notificatable_type' => 'App\Item' , 'content'=>' 4096content ~~', 'created_at' => Carbon::now() , 'updated_at' => Carbon::now()))
        //     ;
        // }
        //$item = $this->items->find(1);
        //$test = $item->notifications()->create($receiver);
        // \App\Notification::insert($receiver);

        // for ($x = 0; $x <= 10; $x++) {
        //     array_push($data_n, array(
        //         array('name'=>'Coder 1', 'rep'=>'4096'))
        //     );
        // }
        


        //return dd($no);
        // return dd(\App\User::all());
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
}
