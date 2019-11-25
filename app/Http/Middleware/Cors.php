<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        //if ($request->isMethod('options')) {
           //return response('', 200)
             // ->header('Access-Control-Allow-Methods', '*')
             //->header('Access-Control-Allow-Headers', '*'); // Add any required headers here
        //}
        //return $next($request);
	if ($request->isMethod('options')) {
	
        $response = $next($request);
        $response->header('Access-Control-Allow-Credentials', 'true');
	$response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Methods', '*');
        $response->header('Access-Control-Allow-Headers', '*');
        return $response;

//return  $next($request)
        //->header("Access-Control-Allow-Origin: *")
        //->header('Access-Control-Allow-Methods: *')
        //->header('Access-Control-Allow-Headers: *')
        //->header('Access-Control-Allow-Credentials: true');
	}
        //if (!$request->isMethod('options')) {
                return $next($request);
       //}


    }
}
