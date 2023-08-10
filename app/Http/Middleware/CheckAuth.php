<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class CheckAuth
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
        if (Session::has('username')) {
            return $next($request);
        } else {
            Session::flush();
            return redirect()->route('login');
        }
    }
}
