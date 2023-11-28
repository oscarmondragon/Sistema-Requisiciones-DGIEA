<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

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
        if (!Session::has('id_user') || !Session::has('id_proyecto') || !Session::has('id_rt')) {
            return redirect('/error-cvu'); // Redirige al usuario al inicio de sesión si alguna variable de sesión no existe
        }

        return $next($request);

    }


}