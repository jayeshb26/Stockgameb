<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class Distributer
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
        if(Session::has('username') && Session::get('role')=="distributer"){
            return $next($request);
        }else{
            return redirect()->route('login');
        }
    }
}
