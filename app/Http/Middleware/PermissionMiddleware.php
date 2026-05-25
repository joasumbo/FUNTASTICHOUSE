<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Admin\UtilizadorController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $section): Response
    {
        $user = auth()->user();

        if (!$user?->hasPermission($section)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Sem permissão.'], 403);
            }

            // Dashboard denial: find first accessible section instead of looping back
            if ($section === 'dashboard') {
                foreach (array_keys(UtilizadorController::SECTIONS) as $s) {
                    if ($s !== 'dashboard' && $user?->hasPermission($s)) {
                        return redirect()->route('admin.' . $s)
                            ->with('error', 'Sem acesso ao dashboard.');
                    }
                }
                return redirect()->route('admin.perfil')
                    ->with('error', 'Sem acesso ao dashboard. Contacte o administrador.');
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'Não tem permissão para aceder a esta secção.');
        }

        return $next($request);
    }
}
