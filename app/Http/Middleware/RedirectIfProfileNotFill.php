<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RedirectIfProfileNotFill
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
        $user = Auth::user();
        if($user->facebook()->first()==null)
            return redirect()->route('get.edit.fb')->withErrors('須先進行Facebook帳號綁定。');
        
/*        else{
            if($user->facebook()->first()==null || $user->phone==null || $user->line_username==null)
                return redirect()->back()->with('message', '須先進行Facebook帳號綁定與填寫連絡電話及Line帳號。');
        }*/
        return $next($request);
    }
}
