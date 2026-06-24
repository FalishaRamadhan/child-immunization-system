<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * Accepts one or many roles:
     * role:admin
     * role:admin,healthcare_worker
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! auth()->check()) {
            abort(403, 'Unauthorized.');
        }

        $userRole = trim(auth()->user()->role);

        if (! in_array($userRole, $roles, true)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}