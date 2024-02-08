<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Documento;
use Illuminate\Support\Facades\Storage;

class DocumentosDescargables extends Component
{

    public $docsDescargables = [];

    public function mount()
    {
        //Consultamos en base de datos los documentos descargables
        $this->docsDescargables = Documento::where('id_requisicion', 0)->where('tipo_documento', 6)->get(); // 6 es el tipo de documento descargable
        //dd($this->docsDescargables);
    }
    public function render()
    {
        return view('livewire.documentos-descargables')->layout('layouts.cvu');
    }

    public function descargarArchivo($rutaDocumento, $nombreDocumento)
    {
        //Obtenemos ruta del archivo
        $rutaArchivo = storage_path('app/' . $rutaDocumento);

        if (Storage::exists($rutaDocumento)) {
            // Obtener la extensión del archivo original
            $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION);
            // Devolver el archivo
            return response()->download($rutaArchivo, $nombreDocumento . '.' . $extension);
        } else {
            abort(404);
        }
    }
}
