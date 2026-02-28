<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class SimpleAuth
{
    public function handle($request, Closure $next)
    {
        if (!Session::get('is_login')) {
            return redirect('/login');
        }

        return $next($request);
    }
}
