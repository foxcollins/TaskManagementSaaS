<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si hay un token en la sesión
        if (!$request->session()->has('token')) {
            session()->flash('error', 'Session expired!');
            return redirect('/'); // Redirigir al login si no hay token
        }

        return $next($request); // Permitir el acceso a la ruta
    }
}
