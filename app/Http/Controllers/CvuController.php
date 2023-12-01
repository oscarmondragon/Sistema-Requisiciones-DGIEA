<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;

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
        ]);
        //revisamos quien se loguio si administrativo o responsable 1:rt 0:administrativo
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
            'tipo_financiamiento'
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