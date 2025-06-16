<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $userRole = $request->user()->role->nama_role;

        if (empty($roles) || in_array($userRole, $roles)) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized. Role required: ' . implode(', ', $roles)], 403);
    }
} 