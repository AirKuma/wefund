<?php

namespace App\Http\Controllers\Discuss;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Repositories\Contracts\PostRepositoryInterface;
use Repositories\Contracts\UserRepositoryInterface;
use Repositories\Contracts\BillboardRepositoryInterface;
use Repositories\Contracts\SubscriptionRepositoryInterface;
use Repositories\Contracts\AdminRepositoryInterface;
use App\Http\Requests\Discuss\BillboardRequest;

class BillboardController extends Controller
{
    protected $posts;
    protected $users;
    protected $billboards;
    protected $subscriptions;
    protected $admins;

    public function __construct(PostRepositoryInterface $posts, UserRepositoryInterface $users,BillboardRepositoryInterface $billboards,SubscriptionRepositoryInterface $subscriptions,AdminRepositoryInterface $admins)
    {
        $this->middleware('auth', ['except' => ['getIndex','getSubscriber']]);
        $this->middleware('username', ['except' => ['getIndex','getSubscriber']]);
        $this->middleware('allow', ['only' => 'postSubscription']);
        $this->middleware('admin', ['except' => ['getIndex','getCreateBillboard','postCreateBillboard','postSubscription','getSubscriber','getAdmin']]);
        
        $this->posts = $posts;
        $this->users = $users;
        $this->billboards = $billboards;
        $this->subscriptions = $subscriptions;
        $this->admins = $admins;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $allbillboards = $this->billboards->where('status',0)->get();
        $billboards = $this->billboards->where('status',0)->paginate(10);

        if(Auth::check()){
            $user = $this->users->find(Auth::id());
            $subscriptions = $user->billboards()->where('status',0)->where('allow',0)->get();
        }

        return View('discuss.billboards.index', compact('billboards','subscriptions','allbillboards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreateBillboard()
    {
        return View('discuss.billboards.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreateBillboard(BillboardRequest $request)
    {
        $user = $this->users->find(Auth::id());
        $college = $user->college()->first();
        $billboard = $college->billboard()->create($request->all());

        $subscription = $billboard->subscriptions()->create(['user_id' => $user->id, 'allow' => 0]);
        $admin = $billboard->admins()->create(['user_id' => $user->id]);

        return redirect()->route('get.billboard.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEditBillboard($id)
    {
        $billboard = $this->billboards->find($id);

        return View('discuss.billboards.edit', compact('billboard'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patchUpdateBillboard(BillboardRequest $request, $id)
    {
        $billboard = $this->billboards->find($id);
        $billboard = $this->billboards->update($request->except('edit','_method', '_token','domain','target','anonymous','name'), $id);
        $rebillboard = $this->billboards->find($id);
        
        return redirect()->route('get.post.index',['billboard' => $rebillboard->domain,'category' => 'all']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyBillboard(Request $request,$id)
    {
        $billboard = $this->billboards->delete($id);
        if($request->returnback!=null)
            return redirect()->back();
        else
            return redirect()->route('get.post.index',['billboard' => 'all','category' => 'all']);
    }

    public function postBlock(Request $request, $id)
    {
        
        $billboard = $this->billboards->find($id);
        if($billboard->status == 1)
            $billboard->status = 0;
        else
            $billboard->status = 1;
        $billboard->save();
        
        if($request->returnback!=null)
            return redirect()->back();
        else
            return redirect()->route('get.post.index',['billboard' => $billboard->domain,'category' => 'all']);
    }

    public function postSubscription(Request $request,$id)
    {
        $billboard = $this->billboards->find($id);
        $user = $this->users->find(Auth::id());

        if($user->subscriptions()->where('subscriptable_id',$id)->first()!=null){
            $subscription = $this->subscriptions->delete($user->subscriptions()->where('subscriptable_id',$id)->first()->id);
            if($billboard->admins()->where('user_id',Auth::id())->first()!=null)
                $admin = $this->admins->delete($billboard->admins()->where('user_id',Auth::id())->first()->id);
        }
        else{
            if($billboard->type==1)
                $subscription = $billboard->subscriptions()->create(['user_id' => $user->id,'allow' => '1']);
            else
                $subscription = $billboard->subscriptions()->create(['user_id' => $user->id]);
        }
        return redirect()->back();
    }

    public function getAdmin()
    {
        $user = $this->users->find(Auth::id());
        $billboards = $user->admin_billboards()->get();
        

        return View('discuss.billboards.admin', compact('billboards'));
    }

    public function getSubscriber($id)
    {
        $billboard = $this->billboards->find($id);
        $subscribers = $billboard->subscriptions()->where('allow',0)->get();
        

        return View('discuss.billboards.subscriber', compact('subscribers','billboard'));
    }

    public function postSetadmin(Request $request,$id)
    {
        $subscription = $this->subscriptions->find($id);
        $billboard = $this->billboards->find($subscription->subscriptable_id);
        $user = $this->users->find($subscription->user_id);

        if($user->admins()->where('adminable_id',$billboard->id)->first()!=null){
            $admin = $this->admins->delete($user->admins()->where('adminable_id',$billboard->id)->first()->id);
        }
        else{
            $admin = $billboard->admins()->create(['user_id' => $user->id]);
        }
        return redirect()->back();
    }

    public function destroySubscriber(Request $request,$id)
    {
        $subscription = $this->subscriptions->find($id);
        $billboard = $this->billboards->find($subscription->subscriptable_id);
        $user = $this->users->find($subscription->user_id);

        if($user->admins()->where('adminable_id',$billboard->id)->first()!=null)
            $admin = $this->admins->delete($user->admins()->where('adminable_id',$billboard->id)->first()->id);

        $subscription = $this->subscriptions->delete($id);

        return redirect()->back();
    }

    public function getApplySubscriber($id)
    {
        $billboard = $this->billboards->find($id);
        $subscribers = $billboard->subscriptions()->where('allow',1)->get();

        return View('discuss.billboards.applysubscriber', compact('subscribers','billboard'));
    }

    public function postRespond(Request $request,$id)
    {  
        //$billboard = $this->billboards->find($billboard);
        //$user = $this->users->find($user);
        $subscription = $this->subscriptions->find($id);

        if($request->allow==0){
            $subscription->allow = 0;
            $subscription->save();
        }else{
            $subscription = $this->subscriptions->delete($subscription->id);
        }

        return redirect()->back();
    }
}
