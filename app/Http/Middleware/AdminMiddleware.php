<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        // Verificar que el usuario tenga el rol de admin
        // Opción 1: Si usas Spatie Permission
        Log::info('Auth::user()->hasRole(admin): ' . Auth::user()->hasRole('administrador'));
        Log::info('Auth::user()->hasRole(admin): ' . Auth::user()->roles);
        if (!Auth::user()->hasRole('administrador')) {
            abort(403, 'No tienes permisos para acceder a esta área');
        }

        // Opción 2: Si tienes un campo 'is_admin' en la tabla users
        // if (!auth()->user()->is_admin) {
        //     abort(403, 'No tienes permisos para acceder a esta área');
        // }

        // Opción 3: Si verificas por email específico
        // $adminEmails = ['admin@example.com', 'tu@email.com'];
        // if (!in_array(auth()->user()->email, $adminEmails)) {
        //     abort(403, 'No tienes permisos para acceder a esta área');
        // }

        return $next($request);
    }
}
