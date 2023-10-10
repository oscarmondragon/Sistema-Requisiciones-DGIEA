<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CvuAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Revisa si las variables de sesion existen
        if (!session()->has('id_user') || !session()->has('id_proyecto') || !session()->has('id_rt')) {
            return redirect('/error-cvu'); // Redirige al usuario al inicio de sesión si alguna variable de sesión no existe
        }

        return $next($request);

    }


}