<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Item;
use App\Image;
use App\Post;
use App\Billboard;
use App\BillboardCategory;
use App\Subscription;
use App\Comment;
use Route;


class RedirectIfNotCreater
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
        if(Route::currentRouteName() == 'get.auction.item.edit' || Route::currentRouteName() == 'patch.auction.item.update' || Route::currentRouteName() == 'post.auction.item.image.upload' || Route::currentRouteName() == 'delete.auction.item.destroy' || Route::currentRouteName() == 'post.auction.item.repost'){
            $item=Item::find($request->id);
            if($item->user_id != Auth::id() && Auth::user()->permission != 1)
                return redirect()->back()->withErrors('無權限編輯！');
        }elseif(Route::currentRouteName() == 'delete.auction.item.image.destroy'){
            $image=Image::find($request->id);
            $user_image = $image->album()->first()->item2()->where('user_id',Auth::id());
            if($user_image->first()==null && Auth::user()->permission != 1)
                return redirect()->back()->withErrors('無權限刪除！');
        }elseif(Route::currentRouteName() == 'get.discuss.post.edit' || Route::currentRouteName() == 'patch.discuss.post.update' || Route::currentRouteName() == 'delete.discuss.post.destroy' || Route::currentRouteName() == 'get.post.block' || Route::currentRouteName() == 'patch.post.setpriority'){
            $post=Post::find($request->id);

            if((Route::currentRouteName() == 'get.post.block' || Route::currentRouteName() == 'patch.post.setpriority') && Auth::user()->permission != 1 && $post->billboard()->first()->admins()->where('user_id',Auth::id())->first() == null)
                return redirect()->back()->withErrors('無權限編輯！');
            elseif($post->user_id != Auth::id() && Auth::user()->permission != 1 && $post->billboard()->first()->admins()->where('user_id',Auth::id())->first() == null)
                return redirect()->back()->withErrors('無權限編輯！');
        }elseif(Route::currentRouteName() == 'get.discuss.billboard.edit' || Route::currentRouteName() == 'patch.discuss.billboard.update' || Route::currentRouteName() == 'delete.discuss.billboard.destroy' || Route::currentRouteName() == 'get.billboard.block' || Route::currentRouteName() == 'get.billboard.applysubscriber' || Route::currentRouteName() == 'get.billboard.category' || Route::currentRouteName() == 'post.billboard.category'){
            $billboard=Billboard::find($request->id);
            if(Auth::user()->permission != 1 && $billboard->admins()->where('user_id',Auth::id())->first() == null)
                return redirect()->back()->withErrors('無權限編輯！');
        }elseif(Route::currentRouteName() == 'post.subscriber.setadmin' || Route::currentRouteName() == 'delete.subscriber.destroy' || Route::currentRouteName() == 'post.subscriber.respond'){
            $subscription=Subscription::find($request->id);
            $billboard=Billboard::find($subscription->subscriptable_id);
            if(Auth::user()->permission != 1 && $billboard->admins()->where('user_id',Auth::id())->first() == null)
                return redirect()->back()->withErrors('無權限編輯！');
        }elseif(Route::currentRouteName() == 'patch.billboard.category.update' || Route::currentRouteName() == 'delete.billboard.category.destroy'){
            $billboardcategory=BillboardCategory::find($request->id);
            $billboard = $billboardcategory->billboard()->first();
            if(Auth::user()->permission != 1 && $billboard->admins()->where('user_id',Auth::id())->first() == null)
                return redirect()->back()->withErrors('無權限編輯！');
        }
        elseif(Route::currentRouteName() == 'patch.comment.update' || Route::currentRouteName() == 'delete.comment.destroy'){
            $comment=Comment::find($request->id);
            if($comment->user_id != Auth::id() && Auth::user()->permission != 1)
                return redirect()->back()->withErrors('無權限編輯！');
        }
        return $next($request);
    }
}
