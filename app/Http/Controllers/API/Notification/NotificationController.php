<?php

namespace App\Http\Controllers\API\Notification;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Transformers\Notification\NotificationTransformers;

use Dingo\Api\Routing\Helpers;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Illuminate\Support\Collection;

use Carbon\Carbon;

class NotificationController extends Controller
{
    use Helpers;

     public function __construct()
    {
        //$this->middleware('apiauth', ['except' => ['getNotification','getUserNotificationCount','postUserNotificationCount']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNotification(Request $request)
    {
        $limit = $request->limit;
        $offset = $request->offset;

        $user = app('Dingo\Api\Auth\Auth')->user();
        $notifications = $user->notifications()->where('notificatable_type','App\Item')->orderBy('created_at', 'desc')->limit($limit)->offset($offset)->get();

        return $this->response->collection($notifications, new NotificationTransformers())->setStatusCode(200);
    }

    public function getUserNotificationCount(Request $request)
    {

        $user = app('Dingo\Api\Auth\Auth')->user();
        $count = $user->notifications()->where('notificatable_type','App\Item')->where('is_read',0)->where('created_at','>',$user->read_notification_at)->count();

        return $count;
    }

    public function postUserNotificationCount(Request $request)
    {

        $user = app('Dingo\Api\Auth\Auth')->user();
        $user->read_notification_at = Carbon::now();
        $user->save();

        return $this->response->array($user)->setStatusCode(200);
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
