<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Adquisicion;
use App\Models\Solicitud;
use App\Models\TipoRequisicion;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class VistosBuenos extends Component
{

    use WithPagination;
    public $tipo;
    public $search = '';
    public $searchVobo = '';
    public $categoria=0;
    public $categoriaVobo=0;
    public $tipoRequisicion ;
    public $f_inicial=0;
    public $f_final=0;

    protected $listeners = ['deleteAdquisicion','deleteSolicitud'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSearcVobo()
    {
        $this->resetPage();
    }
    public function render()
    {
        // $adquisiciones = Adquisicion::where('estatus_general', 1)->orderBy('id')->paginate(3);
        
                $adquisiciones = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
                ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
                ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
                ->select('adquisiciones.id as id','adquisiciones.clave_adquisicion as id_requerimiento','estatus_requisiciones.descripcion as estado',
                'adquisiciones.updated_at as modificacion','cuentas_contables.nombre_cuenta', 'tipo_requisiciones.descripcion') ;   
                //si palabra clave esta vacia no se ejecuta
                if(!empty($this->search)){
                    $adquisiciones->where(function ($query) {
                        $query->where('clave_adquisicion', 'like', '%' . $this->search . '%')
                        ->orWhereHas('requerimiento', function ($query) {
                            $query->where('descripcion', 'like', '%' . $this->search . '%');
                            })->orWhereHas('cuentas', function ($query) {
                            $query->where('nombre_cuenta', 'like', '%' . $this->search . '%');
                            });
                    });
                }
                if($this->f_inicial != 0 and $this->f_final==0 ){
                    //dd('f inicial solo'.$this->f_final.''.$this->f_inicial);
                    $adquisiciones->where('adquisiciones.created_at', 'like', '%' . $this->f_inicial .'%');
                }
                if($this->f_final != 0 and $this->f_inicial==0){
                    //dd('f final solo'.$this->f_final.''.$this->f_inicial);
                    $adquisiciones->where('adquisiciones.created_at', 'like', '%' . $this->f_final .'%');
                }
                if($this->f_final != 0 and $this->f_inicial != 0){
                   // dd('las dos'.$this->f_final.''.$this->f_inicial);
                    $adquisiciones->where('adquisiciones.created_at', 'like', '%' .$this->f_final .'%');

                }
                    
                $adquisiciones->where('estatus_general', 1)->where('id_emisor', '=', session('id_user'));
           
                $solicitudes =  Solicitud::join("cuentas_contables", "solicitudes.id_rubro", "=", "cuentas_contables.id")
                ->join("tipo_requisiciones", "solicitudes.tipo_requisicion", "=", "tipo_requisiciones.id")
                ->join("estatus_requisiciones", "solicitudes.estatus_rt", "=", "estatus_requisiciones.id")
                ->select('solicitudes.id as id','solicitudes.clave_solicitud as id_requerimiento','estatus_requisiciones.descripcion as estado',
                'solicitudes.updated_at as modificacion','cuentas_contables.nombre_cuenta', 'tipo_requisiciones.descripcion');  

                if(!empty($this->search)){
                    $solicitudes->where(function ($query) {
                        $query->where('clave_solicitud', 'like', '%' . $this->search . '%')
                            ->orWhereHas('requerimientoSolicitud', function ($query) {
                                $query->where('descripcion', 'like', '%' . $this->search . '%');
                            })->orWhereHas('rubroSolicitud', function ($query) {
                            $query->where('nombre_cuenta', 'like', '%' . $this->search . '%');
                        });
                    });
                }
                if($this->f_inicial != 0 and $this->f_final==0 ){
                    //dd('f inicial solo'.$this->f_final.''.$this->f_inicial);
                    $solicitudes->where('solicitudes.created_at', 'like', '%' . $this->f_inicial .'%');
                }
                if($this->f_final != 0 and $this->f_inicial==0){
                   // dd('f final solo'.$this->f_final.''.$this->f_inicial);
                    $solicitudes->where('solicitudes.created_at', 'like', '%' . $this->f_final .'%');
                }
                if($this->f_final != 0 and $this->f_inicial != 0){
                   // dd('las dos'.$this->f_final.''.$this->f_inicial);
                    $solicitudes->where('solicitudes.created_at', 'like', '%' .$this->f_final .'%');

                }
                
                $solicitudes->where('estatus_rt', 1)->where('id_emisor', '=', session('id_user'));
       
        $adquisicionesVistosBuenos = Adquisicion::join("cuentas_contables", "adquisiciones.id_rubro", "=", "cuentas_contables.id")
        ->join("tipo_requisiciones", "adquisiciones.tipo_requisicion", "=", "tipo_requisiciones.id")
        ->join("estatus_requisiciones", "adquisiciones.estatus_general", "=", "estatus_requisiciones.id")
        ->select('adquisiciones.id as id','adquisiciones.clave_adquisicion as id_requerimiento','estatus_general as estador',
        'adquisiciones.vobo_admin as vobo_admin','adquisiciones.vobo_rt as vobo_rt','estatus_requisiciones.descripcion as estado',
         'adquisiciones.updated_at as modificacion','cuentas_contables.nombre_cuenta', 'tipo_requisiciones.descripcion', 'adquisiciones.id_emisor')        
        ->where(function ($query) {
            $query->where('clave_adquisicion', 'like', '%' . $this->searchVobo . '%')
                ->orWhereHas('requerimiento', function ($query) {
                    $query->where('descripcion', 'like', '%' . $this->searchVobo . '%');
                })->orWhereHas('cuentas', function ($query) {
                $query->where('nombre_cuenta', 'like', '%' . $this->searchVobo . '%');
            });
        })->where('estatus_general', 2);

        $solicitudesVistosBuenos = Solicitud::join("cuentas_contables", "solicitudes.id_rubro", "=", "cuentas_contables.id")
        ->join("tipo_requisiciones", "solicitudes.tipo_requisicion", "=", "tipo_requisiciones.id")
        ->join("estatus_requisiciones", "solicitudes.estatus_rt", "=", "estatus_requisiciones.id")
        ->select('solicitudes.id as id','solicitudes.clave_solicitud as id_requerimiento','solicitudes.estatus_rt as estador',
        'solicitudes.vobo_admin as vobo_admin','solicitudes.vobo_rt as vobo_rt','estatus_requisiciones.descripcion as estado',
        'solicitudes.updated_at as modificacion','cuentas_contables.nombre_cuenta', 'tipo_requisiciones.descripcion', 'solicitudes.id_emisor')        
        ->where(function ($query) {
            $query->where('clave_solicitud', 'like', '%' . $this->searchVobo . '%')
                ->orWhereHas('requerimientoSolicitud', function ($query) {
                    $query->where('descripcion', 'like', '%' . $this->searchVobo . '%');
                })->orWhereHas('rubroSolicitud', function ($query) {
                $query->where('nombre_cuenta', 'like', '%' . $this->searchVobo . '%');
            });
        })->where('estatus_rt', 2);

        if($this->categoria == 0){
            $requerimientos = $adquisiciones->union($solicitudes)->orderBy('id')->paginate(10,pageName: 'pendientes');
            
         }else if($this->categoria==1 ){    
                   
            $requerimientos = $adquisiciones->orderBy('id')->paginate(10,pageName: 'pendientes');
             
         }else if($this->categoria==2 ){
            $requerimientos = $solicitudes->orderBy('id')->paginate(10,pageName: 'pendientes');            
         }

         if($this->categoriaVobo == 0){
            $juntasvobo =$adquisicionesVistosBuenos->union($solicitudesVistosBuenos)->orderBy('id')->paginate(10, pageName: 'vobo');           
         }else if($this->categoriaVobo==1 ){
            //dd($this->f_final.''.$this->f_inicial);
            $juntasvobo =$adquisicionesVistosBuenos->orderBy('id')->paginate(10, pageName: 'vobo');
         }else if($this->categoriaVobo==2 ){
            $juntasvobo =$solicitudesVistosBuenos->orderBy('id')->paginate(10, pageName: 'vobo');
            //dd($this->f_final.''.$this->f_inicial);
         }

        //$requerimientos = $adquisiciones->union($solicitudes)->orderBy('id')->paginate(10,pageName: 'pendientes');

        return view(
            'livewire.vistos-buenos',
            ['adquisiciones' => $requerimientos, 'adquisicionesVistosBuenos' => $juntasvobo ]
        );
    }



    public function mount()
    {
        $this->tipoRequisicion = TipoRequisicion::select('id', 'descripcion')->where('estatus', 1)->get();
        //  $this->adquisiciones = Adquisicion::where('tipo_requisicion', 1)->orderBy('id')->paginate(3);
    }

    public function deleteAdquisicion($id)
    {
        $adquisicion = Adquisicion::findOrFail($id);
        $adquisicion->delete();
    }
    public function deleteSolicitud($id)
    {
        $adquisicion = Solicitud::findOrFail($id);
        $adquisicion->delete();
    }
    public function filterByCategory($categoria){
       $this->categoria = $categoria;
      // dd($this->categoria);
    }
    public function filterByCategoryVobo($categoria){
       $this->categoriaVobo = $categoria;
      // dd($this->categoria);
    }

}