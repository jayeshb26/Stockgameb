<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class Retailer
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
        if(Session::has('username') && Session::get('role')=="retailer"){
            return $next($request);
        }else{
            return redirect()->route('login');
        }
    }
}
