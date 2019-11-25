<?php

namespace App\Http\Middleware;

use Closure;
use App\Item;

class RedirectIfItemHasEnd
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
        if($item->end_time <= \Carbon\Carbon::now())
            return redirect()->back()->withErrors('物品時間已到，請勿出價！');
        
        return $next($request);
    }
}
