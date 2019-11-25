<?php

namespace App\Http\Controllers\API\Comment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentRequest;
use Repositories\Contracts\CommentRepositoryInterface;

use Dingo\Api\Routing\Helpers;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Illuminate\Support\Collection;

class CommentController extends Controller
{
    use Helpers;
    protected $comments;

    public function __construct(CommentRepositoryInterface $comments)
    {
        $this->middleware('apiauth');
        $this->comments = $comments;
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
    public function destroyComment($id)
    {
        
        $dcomment = $this->comments->find($id);
        $user = app('Dingo\Api\Auth\Auth')->user();
        if($dcomment->user_id != $user->id && $user->permission != 1)
            return $this->response->errorBadRequest();

        $comment = $this->comments->delete($id);

        return $this->response->array($comment)->setStatusCode(200);
    }

    public function putUpdateComment(CommentRequest $request, $id)
    {
        $comment = $this->comments->find($id);

        $user = app('Dingo\Api\Auth\Auth')->user();
        if($comment->user_id != $user->id && $user->permission != 1)
            return $this->response->errorBadRequest();

        $comment = $this->comments->update($request->all(), $id);
        
        return $this->response->array($comment)->setStatusCode(200);
    }

    public function postReport(Request $request, $id)
    {
        
        $comment = $this->comments->find($id);
        $user = app('Dingo\Api\Auth\Auth')->user();

        if($comment->reports()->where('user_id',$user->id)->count()!=0)
            return $this->response->errorBadRequest();

        $report = $comment->reports()->create(['user_id' => $user->id, 'content' => $request->content]);
        
        //舉報三次自動disable
        if($comment->reports()->count()>=3){
            $comment->status=1;
            $comment->save();
        }

        return $this->response->array($comment)->setStatusCode(200);
    }
}
