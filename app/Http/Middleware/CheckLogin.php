<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
{
    public function handle($request, Closure $next)
    {
        if (!session('logged_in')) {
            return redirect()->route('login.show');
        }

        return $next($request);
    }
}
