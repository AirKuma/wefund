<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RedirectIfUsernameNotFill
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
        if($user->username==null)
            return redirect()->route('get.edit.profile')->withErrors('須先填寫使用者名稱，才可以使用討論區。');
        return $next($request);
    }
}
