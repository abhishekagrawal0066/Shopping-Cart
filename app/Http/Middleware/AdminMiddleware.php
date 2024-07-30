<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {

        if (Auth::check() && Auth::user()->is_admin) {   
            return $next($request);
        }

        return redirect()->route('login')->withErrors(['You are not authorized to access this page.']);
    }
}
