<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class Admin
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
        if(Session::has('username') && Session::get('role')=="Admin"){
            return $next($request);
        }else{
            return redirect('/login');
        }
    }
}
