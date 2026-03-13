<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // If not logged in at all, redirect to login page
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // If logged in but not admin, show 403
        if (!$request->user()->is_admin) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return $next($request);
    }
}
