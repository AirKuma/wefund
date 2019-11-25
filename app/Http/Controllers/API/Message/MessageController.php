<?php

namespace App\Http\Controllers\API\Message;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\Contracts\UserRepositoryInterface;
use Repositories\Contracts\MessageRepositoryInterface;
use Repositories\Contracts\ThreadRepositoryInterface;
use App\Transformers\Message\MessageTransformers;
use App\Transformers\Message\Thread\ThreadTransformers;
use App\Http\Requests\Message\MessageRequest;

use Dingo\Api\Routing\Helpers;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Thread;
use App\Message;


class MessageController extends Controller
{
    use Helpers;
    protected $users;
    protected $threads;
    protected $messages;

    public function __construct(UserRepositoryInterface $users, ThreadRepositoryInterface $threads, MessageRepositoryInterface $messages)
    {
        //$this->middleware('auth', ['except' => 'getShowItem']);
        $this->middleware('apiauth', ['except' => ['getThreads','getThread','getMessageInbox','getIfThread','getUserMessageCount','postUserMessageCount']]);
        $this->threads = $threads;
        $this->messages = $messages;
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getThreads(Request $request)
    {

        $limit = $request->limit;
        $offset = $request->offset;

        $user = app('Dingo\Api\Auth\Auth')->user();

   
        $threads = Thread::has('messages')
        ->where('user_one_id', $user->id)
        ->orWhere('user_two_id', $user->id)
        ->has('messages')
        ->get()
        ->sortBy(function($hackathon)
        {
            return $hackathon->messages()->orderBy('messages.created_at', 'desc')->first()->created_at;
        })->reverse()->slice($offset, $limit);
        //  ->limit($limit)->offset($offset);
       
       // $threads = Thread::has('messages', '>=', \DB::raw(1))
       //  ->where('user_one_id', $user->id)
       //  ->orWhere('user_two_id', $user->id)
       //  ->has('messages', '>=', \DB::raw(1))
       //  //->with('messages')
       //  ->leftJoin('messages', 'messages.thread_id', '=', 'threads.id')
       //  ->orderBy('messages.created_at', 'desc')
       //  ->groupBy('threads.id')
       //  ->select('threads.*')
       //  ->get();

        // $threads = Thread::with(array(
        //     'messages' => function ($query) {
        //         $query->orderBy('messages.created_at', 'desc');
        //     }
        // ))
        // ->get();

        // $threads = Thread::with(['messages' => function ($query) {
        //     $query->orderBy('messages.created_at', 'desc');
        // }])->get();

        // $threads = Thread::with('messages')
        // ->select('threads.*')
        // ->orderBy('messages.created_at', 'desc')
        // ->get();

        // $threads = Message::with('thread')
        // ->rightJoin('threads', 'threads.id', '=', 'messages.thread_id')
        // ->where('user_one_id', $user->id)
        // ->orWhere('user_two_id', $user->id)
        // ->orderBy('messages.created_at', 'desc')
        // ->groupBy('threads.id')
        // ->get();


        
        // $threads = Thread::with('messages')->get();

        //
        //return dd($threads);
        //return $this->response->array($threads)->setStatusCode(200);
        return $this->response->collection($threads, new ThreadTransformers())->setStatusCode(200);
        //return $this->response->collection(\Collecton::make($threads), new ThreadTransformers())->setStatusCode(200);
    }

    // public function getThread(Request $request, $id)
    // {

    //     $thread = $this->threads->where('id',$id)->get();
    //     return $this->response->array($thread)->setStatusCode(200);
    // }

    public function getMessageInbox(Request $request, $id)
    {
        $limit = $request->limit;
        $offset = $request->offset;
        //return View('users.account', compact('profile'));
        $receiver_id = $id;
        $user = app('Dingo\Api\Auth\Auth')->user();
        //$threads = $user->threads()->get();
        
        $thread = \DB::table('threads')
        ->select('id')
        ->where('user_one_id', '=', $user->id)
        ->where('user_two_id', '=', $id)
        ->orWhere('user_one_id', $id)
        ->where('user_two_id', '=', $user->id)
        ->first();
        //http://www.bootply.com/JnOYtO9xzn
        $thread_id = $thread->id;
        $messages = $this->threads->find($thread_id)->messages()->orderBy('messages.created_at','desc')->limit($limit)->offset($offset)->get()->reverse();

        return $this->response->collection($messages, new MessageTransformers())->setStatusCode(200);
        //return dd($messages);

        //return dd($threads);
        //return View('messages.inbox', compact('user', 'receiver_id','messages'));

    }


    public function postSendMessage(MessageRequest $request)
    {
        $user = app('Dingo\Api\Auth\Auth')->user();

        $thread = \DB::table('threads')
        ->select('id')
        ->where('user_one_id', '=', $user->id)
        ->where('user_two_id', '=', $request->id)
        ->orWhere('user_one_id', $request->id)
        ->where('user_two_id', '=', $user->id)
        ->first();
        
        if(count($thread) == 0){
            $thread = \DB::table('threads')->insertGetId(
            ['user_one_id' => $user->id , 'user_two_id' => $request->id]
            );
        }else{
            $thread = $thread->id;
        }
        

        $message = \DB::table('messages')->insert([
            ['body' => $request->body, 'user_id' => $user->id, 'thread_id' => $thread, 'created_at' => Carbon::now()]
        ]);

        $receiver = $this->users->find($request->id);
        $count = $this->getMessageCount($request->id);
        event(new \App\Events\PushMessengeRead($receiver, $count));

        return $this->response->array($message)->setStatusCode(200);
        //return redirect()->back();
       // $thread = $this->threads->find($thread)->messages()->get();
        //return dd($thread);
        // $thread = $sender->threads()->create([]);
        // $receiver = \DB::table('thread_participant')->insert([
        //     ['user_id' => $request->id , 'thread_id' => $thread->id]
        // ]);
        // $message = \DB::table('messages')->insert([
        //     ['body' => 'taylor@example.com', 'user_id' => Auth::id(), 'thread_id' => $thread->id]
        // ]);

        //

        //$message = $user->messages()->threads()->create([]);
        //return dd($threads);
        //$user = $this->users->find(Auth::id());
        //$this->threads->users()->all();
        //$thread = $user->threads()->attach();
        //return dd($user->threads()->get());
    }

    public function getIfThread($id)
    {
        $receiver_id = $id;
        $user = app('Dingo\Api\Auth\Auth')->user();
        //$threads = $user->threads()->get();

        $thread = $this->threads
        ->where('user_one_id', $user->id)
        ->where('user_two_id', $id)
        ->orWhere('user_one_id', $id)
        ->where('user_two_id', $user->id)
        ->count();

        return $thread;
    }

    public function postCreateThread(Request $request, $id){

        $user = app('Dingo\Api\Auth\Auth')->user();

        $threadcount = $this->getIfThread($id);
        
        if($threadcount == 0){
            $thread = \DB::table('threads')->insertGetId(
                ['user_one_id' => $user->id , 'user_two_id' => $id]
            );
        }

        return $this->response->array($threadcount)->setStatusCode(200);
    }

    public function postThreadRead($id)
    {
        $thread = $this->threads->find($id);
        $user = app('Dingo\Api\Auth\Auth')->user();
        //$threads = $user->threads()->get();

        $messages = $thread->messages()
        ->whereNotIn('user_id', [$user->id])
        ->where('status', 0)
        ->get();

        //return $messages;

        foreach ($messages as $key => $message) {
                $message->status = 1;
                $message->save();
        }

        return $this->response->array($messages)->setStatusCode(200);
    }

    public function getUserMessageCount(Request $request)
    {

        $user = app('Dingo\Api\Auth\Auth')->user();

        // $count = $this->threads->where('user_one_id', $user->id)
        // ->orWhere('user_two_id', $user->id)
        // // ->get();
        // // ->with(['messages' => function ($query) {
        // //     $query->where('status', 0);
        // // }])->get();
        // ->whereHas('messages', function ($query) use ( $user ){
        //     $query->whereNotIn('user_id', [$user['id']])->where('status', 0);
        // })->get();

        $countuser_one = Thread::whereHas('messages', function ($query) use ( $user ){
            $query->whereNotIn('user_id', [$user['id']])->where('status', 0)->where('created_at','>',$user['read_message_at']);
        })->where('user_one_id', $user->id)
        ->count();

        $countuser_two = Thread::whereHas('messages', function ($query) use ( $user ){
            $query->whereNotIn('user_id', [$user['id']])->where('status', 0)->where('created_at','>',$user['read_message_at']);
        })->where('user_two_id', $user->id)
        ->count();

        $count = $countuser_one + $countuser_two;

        // $count = Thread::has('messages', '>=', \DB::raw(1))
        // ->where('user_one_id', $user->id)
        // ->orWhere('user_two_id', $user->id)
        // ->has('messages', '>=', \DB::raw(1))
        // //->with('messages')
        // ->leftJoin('messages', 'messages.thread_id', '=', 'threads.id')
        // ->where('messages.status','=', 0)
        // //->whereNotIn('user_id', [$user->id])
        // //->groupBy('threads.id')
        // //->select('threads.*')
        // ->get();

        // $count = \DB::table('threads')
        // ->select('id')
        // ->where('user_one_id', '=', $user->id)
        // ->where('user_two_id', '=', $id)
        // ->orWhere('user_one_id', $id)
        // ->where('user_two_id', '=', $user->id)

        // $count = Thread::
        // // ->with(['messages' => function ($query) {
        // //     $query->where('status', 0);
        // // }])->get();
        // whereHas('messages', function ($query) use ( $user ){
        //     $query->whereNotIn('user_id', [$user['id']])->where('status', 0);
        // })->where('user_one_id', $user->id)
        // ->orWhere('user_two_id', $user->id)->get();

        return $count;
    }

    public function postUserMessageCount(Request $request)
    {

        $user = app('Dingo\Api\Auth\Auth')->user();
        $user->read_message_at = Carbon::now();
        $user->save();

        return $this->response->array($user)->setStatusCode(200);
    }

    public function getMessageCount($id)
    {

        $user = $this->users->find($id);

        $countuser_one = Thread::whereHas('messages', function ($query) use ( $user ){
            $query->whereNotIn('user_id', [$user['id']])->where('status', 0)->where('created_at','>',$user['read_message_at']);
        })->where('user_one_id', $user->id)
        ->count();

        $countuser_two = Thread::whereHas('messages', function ($query) use ( $user ){
            $query->whereNotIn('user_id', [$user['id']])->where('status', 0)->where('created_at','>',$user['read_message_at']);
        })->where('user_two_id', $user->id)
        ->count();

        $count = $countuser_one + $countuser_two;

        return $count;
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
