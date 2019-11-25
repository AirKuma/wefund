<?php

namespace App\Http\Controllers\Comment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Repositories\Contracts\CommentRepositoryInterface;
use Repositories\Contracts\VoteRepositoryInterface;
use Repositories\Contracts\UserRepositoryInterface;
use Auth;
use App\Http\Requests\Comment\CommentRequest;

class CommentController extends Controller
{
    protected $comments;
    protected $users;
    protected $votes;

    public function __construct(CommentRepositoryInterface $comments,UserRepositoryInterface $users,VoteRepositoryInterface $votes)
    {
        $this->middleware('auth');
        $this->middleware('admin', ['except' => ['postReport','postBlock','getVote']]);

        $this->comments = $comments;
        $this->users = $users;
        $this->votes = $votes;
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
    public function patchUpdateComment(CommentRequest $request, $id)
    {
        $comment = $this->comments->find($id);
        $comment = $this->comments->update($request->except('_method', '_token'), $id);
        
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyComment($id)
    {
        $comment = $this->comments->delete($id);

        return redirect()->back();
    }

    public function postReport(Request $request, $id)
    {
        
        $comment = $this->comments->find($id);

        if($comment->reports()->where('user_id',Auth::id())->count()!=0)
            return redirect()->back()->withErrors('請勿重複舉報同一留言！');

        $report = $comment->reports()->create(['user_id' => Auth::id(), 'content' => $request->content]);
        
        //舉報三次自動disable
        if($comment->reports()->count()>=3){
            $comment->status=1;
            $comment->save();
        }

        return redirect()->back();
    }

    public function postBlock(Request $request, $id)
    {
        
        $comment = $this->comments->find($id);
        $comment->status = 1;
        $comment->save();
        

        return redirect()->back();
    }

    public function getVote(Request $request,$votetype,$id)
    {  
        $comment = $this->comments->find($id);
        $user = $this->users->find(Auth::id());

        if($user->votes()->where('votable_type','App\Comment')->where('votable_id',$id)->where('vote',$votetype)->first()!=null)
            $vote = $this->votes->delete($user->votes()->where('votable_type','App\Comment')->where('votable_id',$id)->where('vote',$votetype)->first()->id);
        elseif($user->votes()->where('votable_type','App\Comment')->where('votable_id',$id)->first()!=null){
            $vote = $this->votes->delete($user->votes()->where('votable_type','App\Comment')->where('votable_id',$id)->first()->id);
            $vote = $comment->votes()->create(['user_id' => Auth::id(), 'vote' => $votetype]);
        }
        else
            $vote = $comment->votes()->create(['user_id' => Auth::id(), 'vote' => $votetype]);

        return redirect()->back();
    }
}
