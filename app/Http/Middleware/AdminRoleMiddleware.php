<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->role !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401)->setStatusCode(401, 'Unauthorized');
        }

        return $next($request);
    }
}
