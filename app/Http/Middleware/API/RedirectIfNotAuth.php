<?php

namespace App\Http\Middleware\API;

use Closure;
use Dingo\Api\Routing\Helpers;

class RedirectIfNotAuth
{
    use Helpers;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = app('Dingo\Api\Auth\Auth')->user();
        if(!$user)
            return $this->response->errorBadRequest();
        else if($user->actived==0)
            return $this->response->errorBadRequest();
        else
            return $next($request);
    }
}
