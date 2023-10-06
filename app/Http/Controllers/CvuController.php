<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;

class CvuController extends Controller
{
    public function cvuVerificar(Request $request)
    {
        $id_user = $request->input('id_investigador');
        $name_user = '';
        $clave_proyecto = $request->input('id_proyecto');
        $accion = $request->input('id_accion');


        //Busca el proyecto por la clave
        $proyecto = Proyecto::where('CveEntPry', $clave_proyecto)->first();

        if ($proyecto) {

            //Determinamos si el usuario es el rt o el administrativo del proyecto para asignar nombre al usuario de la sesion
            if ($id_user == $proyecto->CveEntEmp_Responsable) {
                $name_user = $proyecto->Nombre_Responsable . ' ' . $proyecto->APaterno_Responsable . ' ' . $proyecto->AMaterno_Responsable;
                $VoBo_Who =1;//Logueo de un responsable técnico
            } else if ($id_user == $proyecto->CveEntEmp_Administrativo) {
                $name_user = $proyecto->Nombre_Administrativo . ' ' . $proyecto->APaterno_Administrativo . ' ' . $proyecto->AMaterno_Administrativo;
                $VoBo_Who =0;//Logueo de un administrativo
            }

            //Creamos la sesion con los datos del proyecto
            session([
                'id_user' => $id_user,
                'name_user' => $name_user,
                'id_proyecto' => $clave_proyecto,
                'name_proyecto' => $proyecto->NomEntPry,
                'clave_espacioAcademico' => $proyecto->CveCenCos,
                'name_espacioAcademico' => $proyecto->NomCenCos,
                'id_rt' => $proyecto->CveEntEmp_Responsable,
                'name_rt' => $proyecto->Nombre_Responsable . ' ' . $proyecto->APaterno_Responsable . ' ' . $proyecto->AMaterno_Responsable,
                'id_administrativo' => $proyecto->CveEntEmp_Administrativo,
                'name_administrativo' => $proyecto->Nombre_Administrativo . ' ' . $proyecto->APaterno_Administrativo . ' ' . $proyecto->AMaterno_Administrativo,
                'tipo_financiamiento' => $proyecto->Tipo_Proyecto,
                'VoBo_Who'=>$VoBo_Who
            ]);

            return view('cvu.index', ['accion' => $accion]);
        } else {
            return "El proyecto no existe";
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
}
