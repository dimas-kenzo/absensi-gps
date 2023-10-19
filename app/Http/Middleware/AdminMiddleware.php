<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            // Jika pengguna adalah admin, arahkan ke tampilan admin
            return redirect('/dashboardadmin');
        }

        return $next($request);
    }
}
