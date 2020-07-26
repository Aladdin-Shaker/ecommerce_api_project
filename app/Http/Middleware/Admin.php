<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Closure;

class Admin
{
    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // authunticated admin
        if (Auth::guard($guard)->check()) {
            return $next($request);
        }
        // unauthenticated admin
        else {
            return $this->sendResult('unauthenticated', [], [], false);
        }
    }
}
