<?php

namespace App\Http\Controllers\Message;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\Contracts\UserRepositoryInterface;
use Repositories\Contracts\MessageRepositoryInterface;
use Repositories\Contracts\ThreadRepositoryInterface;

class MessageController extends Controller
{


    public function __construct(UserRepositoryInterface $users, ThreadRepositoryInterface $threads, MessageRepositoryInterface $messages)
    {
        //$this->middleware('auth', ['except' => 'getShowItem']);
        $this->threads = $threads;
        $this->messages = $messages;
        $this->users = $users;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function postSendMessage(Request $request)
    {
        $sender = $this->users->find(Auth::id());

        $thread = \DB::table('threads')
        ->select('id')
        ->where('user_one_id', '=', Auth::id())
        ->where('user_two_id', '=', $request->id)
        ->orWhere('user_one_id', $request->id)
        ->where('user_two_id', '=', Auth::id())
        ->first();
        
        if(count($thread) == 0){
            $thread = \DB::table('threads')->insertGetId(
            ['user_one_id' => Auth::id() , 'user_two_id' => $request->id]
            );
        }else{
            $thread = $thread->id;
        }
        

        $message = \DB::table('messages')->insert([
            ['body' => $request->body, 'user_id' => Auth::id(), 'thread_id' => $thread]
        ]);

        return redirect()->back();
        $thread = $this->threads->find($thread)->messages()->get();
        return dd($thread);
        // $thread = $sender->threads()->create([]);
        // $receiver = \DB::table('thread_participant')->insert([
        //     ['user_id' => $request->id , 'thread_id' => $thread->id]
        // ]);
        // $message = \DB::table('messages')->insert([
        //     ['body' => 'taylor@example.com', 'user_id' => Auth::id(), 'thread_id' => $thread->id]
        // ]);

        //

        //$message = $user->messages()->threads()->create([]);
        return dd($threads);
        //$user = $this->users->find(Auth::id());
        //$this->threads->users()->all();
        //$thread = $user->threads()->attach();
        //return dd($user->threads()->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getMessageInbox($id)
    {
        //return View('users.account', compact('profile'));
        $receiver_id = $id;
        $user = $this->users->find(Auth::id());
        //$threads = $user->threads()->get();

        $thread = \DB::table('threads')
        ->select('id')
        ->where('user_one_id', '=', Auth::id())
        ->where('user_two_id', '=', $id)
        ->orWhere('user_one_id', $id)
        ->where('user_two_id', '=', Auth::id())
        ->first();
        //http://www.bootply.com/JnOYtO9xzn
        $thread_id = $thread->id;
        $messages = $this->threads->find($thread_id)->messages()->simplePaginate(10);
        //return dd($messages);

        //return dd($threads);
        return View('messages.inbox', compact('user', 'receiver_id','messages'));

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
