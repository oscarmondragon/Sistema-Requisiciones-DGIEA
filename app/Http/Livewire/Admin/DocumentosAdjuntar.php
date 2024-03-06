<?php

namespace App\Http\Livewire\Admin;

use App\Models\Documento;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DocumentosAdjuntar extends Component
{
    use WithFileUploads;

    public $nombreDocumento;
    public $documento;
    public $validarNombre;
    public $validacion;

    public $docsDescargables = [];

    protected $listeners = [
        'eliminarArchivo'
    ];

    protected $rules = [
        'documento' => 'required'
    ];

    protected $messages = [
        'nombreDocumento.required' => 'El nombre del documento no puede estar vacío.',
        'nombreDocumento.max' => 'El nombre del documento es demadiado largo.',
        'documento.required' => 'Documento no puede estar vacío.'
    ];


    public function mount()
    {
        $this->docsDescargables = Documento::where('id_requisicion', 0)->where('tipo_documento', 6)
            ->orderBy('nombre_documento', 'ASC')->get();
    }

    public function render()
    {
        return view('livewire.admin.documentos-adjuntar');
    }

    public function updated($nombreDocumento)
    {
        $this->validateOnly($nombreDocumento);
    }

    public function store()
    {
        $this->validate();
        $convertirCadena = strtr($this->nombreDocumento, " ", "_");

        try {
            DB::beginTransaction();
            Documento::create([
                'id_requisicion' => 0,
                'tipo_requisicion' => 0,
                'ruta_documento' => 'doc-UAEM/' . $convertirCadena . '.' . $this->documento->getClientOriginalExtension(),
                'extension_documento' => $this->documento->getClientOriginalExtension(),
                'nombre_documento' => $convertirCadena,
                'tipo_documento' => 6
            ]);

            DB::commit();
            // Guarda doc en DB
            $this->documento->storeAs('doc-UAEM', $convertirCadena . '.' . $this->documento->getClientOriginalExtension());
            return redirect('/documentos-adjuntar')->with('success', 'Documento guardado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: Al intentar guardar el documento. Intente más tarde.' . $e->getMessage());
        }
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

    public function eliminarArchivo($rutaDoc, $nomDoc)
    {
        $docEliminar = Documento::where('ruta_documento', $rutaDoc)->first();

        if ($docEliminar) {
            $archivo = Storage::disk('local')->exists($rutaDoc);

            if ($archivo) {
                $docEliminar->delete();
                Storage::disk('local')->delete($rutaDoc);

                return redirect('/documentos-adjuntar')->with('success', 'El documento: ' . strtr($nomDoc, "_", " ") . ', fue eliminado correctamente.');
            } else {

                return redirect()->back()->with('error', 'Error: Al intentar eliminar el documento. El archivo no fue encontrado.');
            }
        } else {
            return redirect()->back()->with('error', 'Error: Al intentar eliminar el documento. Puede que el archivo no exista en BD.');
        }
    }

    public function rules()
    {
        $rules = $this->rules;
        $rules['nombreDocumento'][] = 'required';
        $rules['nombreDocumento'][] = 'max:50';
        $rules['nombreDocumento'][] = function ($attribute, $value, $fail) {

            $this->validarNombre = Documento::where('nombre_documento', strtr($this->nombreDocumento, " ", "_"))->first();

            if ($this->validarNombre != null) {
                $fail('El nombre del documento ya existe.');
            }
        };

        return $rules;
    }
}
