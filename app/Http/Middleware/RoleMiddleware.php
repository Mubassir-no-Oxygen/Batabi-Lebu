<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware — restricts routes by user role.
 * Usage in routes: ->middleware('role:admin') or ->middleware('role:farmer,buyer')
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Access denied. You do not have permission to view this page.');
        }

        return $next($request);
    }
}
