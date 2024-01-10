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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {

        $allowedRoles = [1, 2];

        if ($request->user() && !in_array($request->user()->rol, $allowedRoles)) {
            abort(403, 'Acceso no autorizado.');
        }
        return $next($request);
    }
}
