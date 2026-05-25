<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $section): Response
    {
        if (!auth()->user()?->hasPermission($section)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Sem permissão.'], 403);
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'Não tem permissão para aceder a esta secção.');
        }

        return $next($request);
    }
}
