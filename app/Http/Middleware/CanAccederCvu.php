<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Models\AsignacionProyecto;
use App\Models\Adquisicion;
use App\Models\Solicitud;



class CanAccederCvu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //obtenemos el id de la ruta
        $id = $request->route('id');

        //Buscamos la palabra adquisiciones en el path de la ruta
        if (strpos($request->path(), 'adquisiciones') !== false) {
            $adquisicion = Adquisicion::find($id);
            if ($adquisicion) {
                //Si son diferentes, entonces no esta autorizado para entrar
                if ($adquisicion->clave_proyecto != Session('id_proyecto')) {
                    abort(403, 'Acceso no autorizado');
                }
            }
            //Buscamos la palabra solicitudes en el path de la ruta
        } else if (strpos($request->path(), 'solicitudes') !== false) {
            $solicitud = Solicitud::find($id);
            if ($solicitud) {
                //Si son diferentes, entonces no esta autorizado para entrar
                if ($solicitud->clave_proyecto != Session('id_proyecto')) {
                    abort(403, 'Acceso no autorizado');
                }
            }

        }



        return $next($request);

    }


}