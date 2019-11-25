<?php

namespace App\Http\Controllers\Discuss;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Repositories\Contracts\PostRepositoryInterface;
use Repositories\Contracts\UserRepositoryInterface;
use Repositories\Contracts\BillboardRepositoryInterface;
use Repositories\Contracts\VoteRepositoryInterface;
use Repositories\Contracts\BookmarkRepositoryInterface;
use Repositories\Contracts\BillboardCategoryRepositoryInterface;
use App\Http\Requests\Discuss\PostRequest;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Requests\Discuss\PriorityRequest;
use Repositories\Contracts\NotificationRepositoryInterface;

class PostController extends Controller
{

    protected $posts;
    protected $users;
    protected $billboards;
    protected $votes;
    protected $bookmarks;
    protected $billboardcategories;

    public function __construct(PostRepositoryInterface $posts, UserRepositoryInterface $users,BillboardRepositoryInterface $billboards,VoteRepositoryInterface $votes,BookmarkRepositoryInterface $bookmarks,BillboardCategoryRepositoryInterface $billboardcategories,NotificationRepositoryInterface $notifications)
    {
        $this->middleware('auth', ['except' => ['getIndex','getShowPost']]);
        $this->middleware('username', ['except' => ['getIndex','getShowPost','getVote','getBookmark','postReport','getMyPost']]);
        $this->middleware('allow', ['only' => 'getShowPost']);
        $this->middleware('admin', ['except' => ['getIndex','getCreatePost','postCreatePost','getShowPost','postComment','getVote','getBookmark','postReport','getMyPost']]);

        $this->posts = $posts;
        $this->users = $users;
        $this->billboards = $billboards;
        $this->votes = $votes;
        $this->bookmarks = $bookmarks;
        $this->billboardcategories = $billboardcategories;
        $this->notifications = $notifications;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex($billboard,$category)
    {
        if(!Auth::check()){
            $subscriptions = $this->billboards->whereIn('id',[1])->where('status',0)->get();
            $billboards = $this->billboards->whereIn('id',[1])->where('status',0)->lists('id')->toArray();
            $allcount = $this->posts->whereIn('billboard_id',$billboards)->where('status',0)->count();
        }else{
            $user = $this->users->find(Auth::id());
            $subscriptions = $user->billboards()->where('status',0)->where('allow',0)->get();
            $billboards = $user->billboards()->where('status',0)->where('allow',0)->lists('subscriptable_id')->toArray();
            $allcount = $this->posts->whereIn('billboard_id',$billboards)->where('status',0)->count();
        }
        if($billboard!='all'){
            $billboarddata = $this->billboards->all()->where('domain',$billboard)->first();
            $posts = $billboarddata->posts()->where('status',0)->orderBy('priority','desc')->orderBy('created_at','desc');
            $categories = $billboarddata->categories()->get();
            if($category!='all'){
                $categoryfind = \APP\BillboardCategory::find($category);
                $posts = $categoryfind->posts()->where('status',0)->orderBy('priority','desc')->orderBy('created_at','desc');
            }
        }
        else{
            $posts = $this->posts->whereIn('billboard_id',$billboards)->where('status',0)->orderBy('created_at','desc');
        }    
        $posts = $posts->paginate(25);
        //$allpost = $this->posts->where('status',0);
        $allbillboard = $this->billboards->all();
        //$allpost = \App\Post::where('status',0);
        //return dd($allpost->where('billboard_id',3)->count());
        return View('discuss.posts.index', compact('subscriptions','billboard','billboarddata','posts','allcount','allbillboard','categories','category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreatePost($type,$domain)
    {
        $user = $this->users->find(Auth::id());
        $billboards = $user->billboards()->where('status',0)->where('allow',0)->get();
        
        if($domain!='all')
        $categories = $this->billboards->where('domain',$domain)->first()->categories()->lists('name','id')->toArray();
        //return dd($categories);
        

        return View('discuss.posts.create',compact('type','billboards','domain','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreatePost(PostRequest $request)
    {
        $user = $this->users->find(Auth::id());
        $billboard = $this->billboards->find($request->billboard_id);

        //check billboard_id
        $subscriptions = $user->billboards()->where('status',0)->where('allow',0)->where('billboards.id',$billboard->id);
        if($subscriptions->first()==null)
            return redirect()->back()->withErrors('無法選此討論版！');
        //check category_id
        if($request->category_id!=null){
            $categories = $billboard->categories()->where('id',$request->category_id);
            if($categories->first()==null)
            return redirect()->back()->withErrors('無法選此分類！');
        }
       
        if($billboard->limit_college==1 && $user->college()->first()->id != $billboard->college_id && $billboard->admins()->where('user_id',$user->id)->first()==null)
            return redirect()->back()->withErrors('此討論版只限定'.$billboard->college()->first()->name.'學生發言');
        
        $post = $user->post()->create($request->all());
        if($billboard->anonymous==1){
            $post->anonymous = 1;
            $post->save();
        }

        $billboard = $this->billboards->find($request->billboard_id);

        if($request->category_id!=null){
            $post = $this->posts->find($post->id);
            $post->billboards()->attach(Auth::id(), ['billboardcategory_id' => $request->category_id]);
        }

        $subscribers = $billboard->subscribers()->whereNotIn('user_id', [Auth::id()])->groupBy("user_id")->get();
        $content_subscriber = $billboard->name. ' 有新文章 ['. $post->title.']';

        $link = '/discuss/post/show/'. $billboard->domain .'/'. $post->id;

        $this->notifications->postNotificationToUsers($subscribers, $content_subscriber, $link, $post->id ,'App\Post');

        return redirect()->route('get.discuss.post.show',['id' =>  $post->id,'billboard' => $billboard->domain]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShowPost($billboard,$id)
    {
        if(!Auth::check()){
            $subscriptions = $this->billboards->whereIn('id',[1])->where('status',0)->get();
            $billboards = $this->billboards->whereIn('id',[1])->where('status',0)->lists('id')->toArray();
            $allcount = $this->posts->whereIn('billboard_id',$billboards)->where('status',0)->count();
        }else{
            $user = $this->users->find(Auth::id());
            $subscriptions = $user->billboards()->where('status',0)->where('allow',0)->get();
            $billboards = $user->billboards()->where('status',0)->where('allow',0)->lists('subscriptable_id')->toArray();
            $allcount = $this->posts->whereIn('billboard_id',$billboards)->where('status',0)->count();
        }
        $billboard_id = $this->billboards->all()->where('domain',$billboard)->first()->id;
        $post = $this->posts->whereId($id)->where('billboard_id',$billboard_id)->first();

        //find通知
        if(Auth::check()){
            $notifications = Auth::user()->notifications()->where('notificatable_id', $id)->where('notificatable_type','App\Post')->where('is_read',0)->lists('id')->toArray();
            $reads = $this->notifications->whereIn('id',$notifications)->get();

            foreach ($reads as $key => $read) {
                $read->is_read = 1;
                $read->save();
            }
        }
        
        if(!$post)
                return View('errors.post');

        //$post = $this->posts->find($id);
        $allbillboard = $this->billboards->all();

        return View('discuss.posts.show',compact('post','subscriptions','billboard','allcount','allbillboard'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEditPost($id,$domain)
    {
        $user = $this->users->find(Auth::id());
        $categories = $this->billboards->where('domain',$domain)->first()->categories()->lists('name','id')->toArray();
        //$billboards = $user->billboards()->where('status',0)->where('allow',0)->lists('name','subscriptable_id');
        $billboards = $user->billboards()->where('status',0)->where('allow',0)->get();
        //$post = $this->posts->find($id);
        $billboard_id = $this->billboards->all()->where('domain',$domain)->first()->id;
        $post = $this->posts->whereId($id)->where('billboard_id',$billboard_id)->first();
        $type = 'content';
        if($post->link!=null)
            $type = 'link';

        return View('discuss.posts.edit', compact('type','post','billboards','domain','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patchUpdatePost(PostRequest $request,$id)
    {
        $billboard = $this->billboards->find($request->billboard_id);
        //return dd($billboard->domain);

        //check category_id
        if($request->category_id!=null){
            $categories = $billboard->categories()->where('id',$request->category_id);
            if($categories->first()==null)
            return redirect()->back()->withErrors('無法選此分類！');
        }

        $post = $this->posts->find($id);
        //return dd($post->pivot->first()->billboardcategory_id);
        if($post->billboards()->first()==null && $request->category_id!=null)
            $post->billboards()->attach(Auth::id(), ['billboardcategory_id' => $request->category_id]);
        elseif($post->billboards()->first()!=null && $request->category_id==null)
            $post->billboards()->detach($post->billboards()->first()->id);
        elseif($request->category_id!=null){
            $post->billboards()->updateExistingPivot($post->billboards()->first()->id,['billboardcategory_id' => $request->category_id],false);
        }

        $post = $this->posts->update($request->except('category_id','type','_method', '_token','billboard_id'), $id);

        return redirect()->route('get.discuss.post.show',['id' =>  $id,'billboard' => $billboard->domain]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyPost(Request $request,$id)
    {
        $post = $this->posts->delete($id);
        if($request->returnback!=null)
            return redirect()->back();
        else
            return redirect()->route('get.post.index',['billboard' => 'all','category' => 'all']);
    }

    public function postComment(CommentRequest $request, $id)
    {  
        $user = $this->users->find(Auth::id());
        $post = $this->posts->find($id);
        $billboard = $this->billboards->find($post->billboard_id);
        if($billboard->limit_college==1 && $user->college()->first()->id != $billboard->college_id && $billboard->admins()->where('user_id',$user->id)->first()==null)
            return redirect()->back()->withErrors('此討論版只限定'.$billboard->college()->first()->name.'學生發言');
       
        if($billboard->anonymous==0 && $request->anonymous!=null)
            $anonymous = $request->anonymous;
        elseif($billboard->anonymous==1)
            $anonymous = 1;
        else
            $anonymous = 0;
        $comment = $post->comments()->create(['user_id' => Auth::id(), 'content' => $request->content,'anonymous' => $anonymous]);

        $post_creater = $post->user()->get();

        if($post_creater->first()->id != $user->id){
        $content_creater = ' 你的文章「'. $post->title.'」有新的留言';

        $link = '/discuss/post/show/'. $billboard->domain .'/'. $post->id;

        $this->notifications->postNotificationToUsers($post_creater, $content_creater, $link, $post->id ,'App\Post');
        }
        // if(!$comment)
        //     return Response::json(['status'=>'error', 'message'=>$request->messages()]);
        return redirect()->back();
    }

    public function getVote(Request $request,$votetype,$id)
    {  
        $post = $this->posts->find($id);
        $user = $this->users->find(Auth::id());

        if($user->votes()->where('votable_type','App\Post')->where('votable_id',$id)->where('vote',$votetype)->first()!=null)
            $vote = $this->votes->delete($user->votes()->where('votable_type','App\Post')->where('votable_id',$id)->where('vote',$votetype)->first()->id);
        elseif($user->votes()->where('votable_type','App\Post')->where('votable_id',$id)->first()!=null){
            $vote = $this->votes->delete($user->votes()->where('votable_type','App\Post')->where('votable_id',$id)->first()->id);
            $vote = $post->votes()->create(['user_id' => Auth::id(), 'vote' => $votetype]);
        }
        else
            $vote = $post->votes()->create(['user_id' => Auth::id(), 'vote' => $votetype]);

        return redirect()->back();
    }

    public function getBookmark(Request $request,$id)
    {
        $post = $this->posts->find($id);
        $user = $this->users->find(Auth::id());

        if($user->bookmarks()->where('bookmarkable_type','App\Post')->where('bookmarkable_id',$id)->first()!=null)
            $bookmark = $this->bookmarks->delete($user->bookmarks()->where('bookmarkable_type','App\Post')->where('bookmarkable_id',$id)->first()->id);
        else
            $bookmark = $post->bookmarks()->create(['user_id' => $user->id]);

        return redirect()->back();
    }

    public function postReport(Request $request, $id)
    {
        
        $post = $this->posts->find($id);

        if($post->reports()->where('user_id',Auth::id())->count()!=0)
            return redirect()->back()->withErrors('請勿重複舉報同一文章！');

        $report = $post->reports()->create(['user_id' => Auth::id(), 'content' => $request->content]);
        
        //舉報三次自動disable
        if($post->reports()->count()>=3 && $post->status==0){
            $post->status=1;
            $post->save();
        }

        return redirect()->back();
    }

    public function postBlock(Request $request, $id)
    {
        
        $post = $this->posts->find($id);
        $post->status = 1;
        $post->save();
        
        if($request->returnback!=null)
            return redirect()->back();
        else
            return redirect()->route('get.post.index',['billboard' => $post->billboard()->first()->domain,'category' => 'all']);
    }

    public function getMyPost($type)
    {
        $user = $this->users->find(Auth::id());
        if($type=='bookmark')
            $posts = $user->user_bookmarks()->where('bookmarkable_type','App\Post');
        else
            $posts = $user->post();

        $posts = $posts->paginate(10);
        
        return View('discuss.posts.mypost',compact('type','posts'));
    }

    public function patchSetpriority(PriorityRequest $request, $id)
    {       
        $post = $this->posts->find($id);
        $post->priority = $request->priority;
        $post->save();
        
        return redirect()->back();
    }
}
