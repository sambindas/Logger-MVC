<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class CheckSession
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
        if(!$request->session()->get('state_id')){
            //if user not authenticated get get requested uri
            session()->put('redirectPath',$request->path());
            return view('/support/auth/login');
        }
        return $next($request);
    }
}
