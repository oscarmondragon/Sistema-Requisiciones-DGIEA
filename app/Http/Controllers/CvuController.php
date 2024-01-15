<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\AsignacionProyecto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;
use Carbon\Carbon;

class CvuController extends Controller
{

    public $paginaCVU;

    public function cvuVerificar(Request $request)
    {
        $id_user = $request->input('id_investigador');
        $clave_proyecto = $request->input('id_proyecto');
        $accion = $request->input('id_accion');

        //Busca el proyecto por la clave
        $proyecto = Proyecto::where('CveEntPry', $clave_proyecto)->first();

        if ($proyecto) { //si existe el poryecto entra aqui
            //Revisamos que el id_user pertenezca a un rt o administrativo del proyecto
            if ($id_user != $proyecto->CveEntEmp_Responsable && $id_user != $proyecto->CveEntEmp_Administrativo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario no es Responsable Técnico ni Administrativo del proyecto'
                ]);
            }
            //revisamos que la accion sea valida
            if ($accion != 1 && $accion != 2 && $accion != 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'La accion no es valida'
                ]);
            }

            //si los datos son correctos lo redireccionamos
            $redirectUrl = route('cvu.verificado', [
                'id_user' => $id_user,
                'clave_proyecto' => $clave_proyecto,
                'accion' => $accion
            ]);

            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl
            ]);

        } else {

            return response()->json([
                'success' => false,
                'message' => 'No fue encontrado el proyecto en la base de datos.'
            ]);
        }
    }

    public function crearSesion(Request $request)
    {
        $id_user = request()->query('id_user');
        $clave_proyecto = request()->query('clave_proyecto');
        $accion = request()->query('accion');        //Busca el proyecto por la clave
        $proyecto = Proyecto::where('CveEntPry', $clave_proyecto)->first();
      
        $asigna_proyecto = AsignacionProyecto::where('id_proyecto',$clave_proyecto);
  
        //Matamos la sesion anterior por si existe
        session()->forget([
            'id_user',
            'name_user',
            'id_proyecto',
            'name_proyecto',
            'clave_espacioAcademico',
            'name_espacioAcademico',
            'id_rt',
            'name_rt',
            'id_administrativo',
            'name_administrativo',
            'tipo_financiamiento',
            'clave_uaem',
            'clave_dygcyn',
            'tiempo_restante_solicitudes',
            'tiempo_restante_adquisiciones',
            'iniciar_captura',
            'mensaje',
            'mensajeSolciitudes',
            'mensajeAdquisiciones'
        ]);
        //revisamos quien se logueo si administrativo o responsable 1:rt 0:administrativo
        if ($id_user == $proyecto->CveEntEmp_Responsable) {
            $name_user = $proyecto->Nombre_Responsable . ' ' . $proyecto->APaterno_Responsable . ' ' . $proyecto->AMaterno_Responsable;
            $VoBo_Who = 1; //Logueo de un responsable técnico
        } else if ($id_user == $proyecto->CveEntEmp_Administrativo) {
            $name_user = $proyecto->Nombre_Administrativo . ' ' . $proyecto->APaterno_Administrativo . ' ' . $proyecto->AMaterno_Administrativo;
            $VoBo_Who = 0; //Logueo de un administrativo
        }
        //Creamos las nuevas variables de sesion

        Session::put('id_user', $id_user);
        Session::put('name_user', $name_user);
        Session::put('id_proyecto', $clave_proyecto);
        Session::put('name_proyecto', $proyecto->NomEntPry);
        Session::put('clave_espacioAcademico', $proyecto->CveCenCos);
        Session::put('name_espacioAcademico', $proyecto->NomCenCos);
        Session::put('id_rt', $proyecto->CveEntEmp_Responsable);
        Session::put('name_rt', $proyecto->Nombre_Responsable . ' ' . $proyecto->APaterno_Responsable . ' ' . $proyecto->AMaterno_Responsable);
        Session::put('id_administrativo', $proyecto->CveEntEmp_Administrativo);
        Session::put('name_administrativo', $proyecto->Nombre_Administrativo . ' ' . $proyecto->APaterno_Administrativo . ' ' . $proyecto->AMaterno_Administrativo);
        Session::put('tipo_financiamiento', $proyecto->Tipo_Proyecto);
        Session::put('VoBo_Who', $VoBo_Who);
        Session::put('clave_uaem', $proyecto->CvePryUaem);
        Session::put('clave_dygcyn', $proyecto->Clave_DIGCYN);
        
        if($asigna_proyecto->count()==0 || $asigna_proyecto->first()->fecha_inicio == null || $asigna_proyecto->first()->fecha_limite_solicitudes ==null){
            $iniciar_captura=0;
            $mensaje="Aún no puedes crear requisiciones";
            Session::put('mensaje',$mensaje);                               
        }else{
            $hoy = now();
            $hoyf = now()->format('Y-m-d');

            $tiempo_restante_solicitudes= $hoy->diffInDays($asigna_proyecto->first()->fecha_limite_solicitudes,false);
            $tiempo_restante_adquisiciones = $hoy->diffInDays($asigna_proyecto->first()->fecha_limite_adquisiciones,false);

            $mensaje =$mensajeSolicitudes=$mensajeAdquisiones="";
            $iniciar_captura=0;

            if($hoyf >= $asigna_proyecto->first()->fecha_inicio){
                //dd(':comenzara  capturar:'.$tiempo_restante_solicitudes.":".$tiempo_restante_adquisiciones);
                $iniciar_captura=1;
                if($hoyf < $asigna_proyecto->first()->fecha_limite_solicitudes ){
                //dd('ya');
                    $tiempo_restante_solicitudes = $tiempo_restante_solicitudes+2;
                    $mensajeSolicitudes="Te quedan ".$tiempo_restante_solicitudes." dias para registrar solicitudes de recursos. Límite ".$asigna_proyecto->first()->fecha_limite_solicitudes."\n";
                }else if($hoyf == $asigna_proyecto->first()->fecha_limite_solicitudes ){
                    $tiempo_restante_solicitudes = $tiempo_restante_solicitudes;
                    $mensajeSolicitudes="Te queda 1 día para registrar solicitudes de recursos. Límite ".$asigna_proyecto->first()->fecha_limite_solicitudes."\n";
                
                }else{
                    $mensajeSolicitudes="Se acabó tu tiempo para registrar solicitudes de recursos el día ".$asigna_proyecto->first()->fecha_limite_solicitudes;
                }  
                if($hoyf < $asigna_proyecto->first()->fecha_limite_adquisiciones){
                    $tiempo_restante_adquisiciones =$tiempo_restante_adquisiciones+2;
                    $mensajeAdquisiones='Te quedan '.$tiempo_restante_adquisiciones.' dias para registrar adquisiciones de bienes y servicios. Límite '.$asigna_proyecto->first()->fecha_limite_adquisiciones."\n";
                
                }else  if($hoyf == $asigna_proyecto->first()->fecha_limite_adquisiciones){
                    $mensajeAdquisiones="Te queda 1 día para registrar adquisiciones de bienes y servicios. Límite ".$asigna_proyecto->first()->fecha_limite_adquisiciones."\n";      
                }   else{       
                    $mensajeAdquisiones ='Se acabó tu tiempo para registrar adquisiciones de bienes y servicios el día '.$asigna_proyecto->first()->fecha_limite_adquisiciones;
                }    
            }else{
                //dd('2. Aun no puedes crear requisiciones para este proyecto');
                $mensaje="Aun no puedes crear requisiciones para este proyecto";
                $iniciar_captura=0;
            }
            Session::put('tiempo_restante_solicitudes', $tiempo_restante_solicitudes);
            Session::put('tiempo_restante_adquisiciones', $tiempo_restante_adquisiciones);
            Session::put('mensaje', $mensaje);
        Session::put('mensajeSolciitudes', $mensajeSolicitudes);
        Session::put('mensajeAdquisiciones', $mensajeAdquisiones);
        }
        
        Session::put('iniciar_captura', $iniciar_captura);
        

        //redireccionamos de acuerdo a la accion seleccionada
        if ($accion == 1) {
            return Redirect::route('cvu.create');
        } else if ($accion == 2) {
            return Redirect::route('cvu.vobo');
        } else if ($accion == 3) {
            return Redirect::route('cvu.seguimiento');
        } else {
            return "La acción solicitada no es valida";
        }
    }

    public function create()
    {
        return view('cvu.index', ['accion' => 1]);
    }

    public function darVobo()
    {
        return view('cvu.index', ['accion' => 2]);
    }

    public function seguimiento()
    {
        return view('cvu.index', ['accion' => 3]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Auth::guard('web')->logout();

        // $request->session()->invalidate();

        //$request->session()->regenerateToken();
        $request->session()->forget([
            'id_user',
            'name_user',
            'id_proyecto',
            'name_proyecto',
            'clave_espacioAcademico',
            'name_espacioAcademico',
            'id_rt',
            'name_rt',
            'id_administrativo',
            'name_administrativo',
            'tipo_financiamiento',
            'tiempo_restante_solicitudes',
            'tiempo_restante_adquisiciones',
            'iniciar_captura',
            'mensaje',
            'mensajeSolciitudes',
            'mensajeAdquisiciones'
        ]);

        request()->flush();

        $this->paginaCVU = env('PAGINA_CVU', 'http://www.siea.uaemex.mx/cvu/');
        return redirect($this->paginaCVU);
    }

    public function error()
    {
        return view('errores.error-cvu');
    }
}