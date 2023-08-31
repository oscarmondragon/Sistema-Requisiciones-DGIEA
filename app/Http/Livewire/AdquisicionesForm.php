<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class AdquisicionesForm extends Component
{

    use WithFileUploads;
    public $tipoRequisicion = '1';
    public $rubro;
    public $bienes;
    public $docsCartaExclusividad = [];
    public $docsCotizacionesFirmadas = [];
    public $docsCotizacionesPdf = [];




    public bool $afectaInvestigacion;
    public $afectaJustificacion;
    public bool $exclusividad;
    public $cartaExclusividad = [];
    public $cotizacionFirmada = [];
    public $cotizacionesPdf = [];
    public bool $vobo;

    public $listeners = [
        'addBien' => 'setBien',
    ];

    public function mount()
    {
        $this->bienes = collect();
        $this->docsCartaExclusividad = [];
        $this->docsCotizacionesFirmadas = [];
        $this->docsCotizacionesPdf = [];
    }
    public function render()
    {
        return view('livewire.adquisiciones-form');
    }

    public function setBien($_id, $descripcion, $importe, $justificacionSoftware, $numAlumnos, $numProfesores, $numAdministrativos, $rubro)
    {
        $this->bienes = collect($this->bienes); //asegurar que bienes sea una coleccion

        if ($_id == 0) { //entramos aqui si el item es nuevo
            // Genera un nuevo ID para el elemento
            $newItemId = $this->bienes->max('_id') + 1;

            if ($rubro === '3') {
                //Agregamos el bien en la coleccion
                $this->bienes->push(['_id' => $newItemId, 'descripcion' => $descripcion, 'importe' => $importe, 'justificacionSoftware' => $justificacionSoftware, 'numAlumnos' => $numAlumnos, 'numProfesores' => $numProfesores, 'numAdministrativos' => $numAdministrativos]);
            } else {
                //Agregamos el bien en la coleccion
                $this->bienes->push(['_id' => $newItemId, 'descripcion' => $descripcion, 'importe' => $importe]);
            }

        } else {
            //Si entra aqui es por que entro a la funcion editar, entonces buscamos el item en la collecion por su id
            $item = $this->bienes->firstWhere('_id', $_id);

            if ($item) {
                //actualizamos el item si existe en la busqueda
                $item['descripcion'] = $descripcion;
                $item['importe'] = $importe;
                $item['justificacionSoftware'] = $justificacionSoftware;
                $item['numAlumnos'] = $numAlumnos;
                $item['numProfesores'] = $numProfesores;
                $item['numAdministrativos'] = $numAdministrativos;


                //Devolvemos la nueva collecion
                $this->bienes = $this->bienes->map(function ($bien) use ($_id, $descripcion, $importe, $justificacionSoftware, $numAlumnos, $numProfesores, $numAdministrativos) {
                    if ($bien['_id'] == $_id) {
                        $bien['descripcion'] = $descripcion;
                        $bien['importe'] = $importe;
                        $bien['justificacionSoftware'] = $justificacionSoftware;
                        $bien['numAlumnos'] = $numAlumnos;
                        $bien['numProfesores'] = $numProfesores;
                        $bien['numAdministrativos'] = $numAdministrativos;
                    }
                    return $bien;
                });
                //actualizamos indices
                $this->bienes = $this->bienes->values();

            }

        }

    }

    public function deleteBien($bien)
    {
        $this->bienes->forget($bien);
    }
    public function resetearBienes()
    {
        $this->bienes = collect();
    }



    public function eliminarArchivo($tipoArchivo, $index)
    {
        if ($tipoArchivo === 'cotizacionesPdf') {
            unset($this->docsCotizacionesPdf[$index]);
            $this->docsCotizacionesPdf = array_values($this->docsCotizacionesPdf);

        } else if ($tipoArchivo === 'cotizacionesFirmadas') {
            unset($this->docsCotizacionesFirmadas[$index]);
            $this->docsCotizacionesFirmadas = array_values($this->docsCotizacionesFirmadas);
        } else if ($tipoArchivo === 'cartasExclusividad') {
            unset($this->docsCartaExclusividad[$index]);
            $this->docsCartaExclusividad = array_values($this->docsCartaExclusividad);
        }

    }
}