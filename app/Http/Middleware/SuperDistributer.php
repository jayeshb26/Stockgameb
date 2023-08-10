<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class SuperDistributer
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
        if(Session::has('username') && Session::get('role')=="superDistributer"){
            return $next($request);
        }else{
            return redirect()->route('login');
        }
    }
}
