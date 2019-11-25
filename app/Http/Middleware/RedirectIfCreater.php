<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Item;

class RedirectIfCreater
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
        $item=Item::find($request->id);
        if($item->user()->first()->id== Auth::id())
            return redirect()->back()->withErrors('物品發起者請勿出價！');
        
        return $next($request);
    }
}
