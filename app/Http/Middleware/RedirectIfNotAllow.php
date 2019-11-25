<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Post;
use App\Notification;
use App\Billboard;

class RedirectIfNotAllow
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(\Request::segment(3)=='show'){
            $post=Post::find($request->id);
            if(!$post){
                //find通知
                if(Auth::check()){
                    $notifications = Auth::user()->notifications()->where('notificatable_id', $request->id)->where('notificatable_type','App\Post')->where('is_read',0)->lists('id')->toArray();
                    $reads = Notification::whereIn('id',$notifications)->get();

                    foreach ($reads as $key => $read) {
                        $read->is_read = 1;
                        $read->save();
                    }
                }
                return View('errors.post',compact('post'));
            }
            if(($post->billboard()->first()->type == 1 && (!Auth::check() || $post->billboard()->first()->subscriptions()->where('user_id',Auth::user()->id)->first()==null)) || ($post->billboard()->first()->target == 1 && (!Auth::check() || Auth::user()->gender!=1)) || ($post->billboard()->first()->target == 2 && (!Auth::check() || Auth::user()->gender!=0)))
                return redirect()->route('get.post.index',['billboard' => 'all','category' => 'all'])->withErrors('無法查看此貼文');
        }else{
            $billboard=Billboard::find($request->id);
            if(($billboard->target == 1 && (!Auth::check() || Auth::user()->gender!=1)) || ($billboard->target == 2 && (!Auth::check() || Auth::user()->gender!=0)))
                return redirect()->back()->withErrors('無法訂閱該版');
        }
        return $next($request);
    }
}
