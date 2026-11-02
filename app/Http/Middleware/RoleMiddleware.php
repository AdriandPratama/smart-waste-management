<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if ($request->user()->role !== $role) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'You do not have permission to access this resource.',
                ], 403);
            }

            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
