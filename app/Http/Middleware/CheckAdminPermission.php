<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = auth('admin')->user();

        if (! $admin) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (! $admin->$permission) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
