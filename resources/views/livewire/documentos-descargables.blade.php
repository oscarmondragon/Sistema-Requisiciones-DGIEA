<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    @include('layouts.header-cvu', ['accion' => 4])
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">            
            <div class="p-6 text-gray-900">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-10">
                    <h3>Documentos descargables </h3>
                        <div class="bg-white overflow-hidden  sm:rounded-lg">
                            <br>
                            <ul>
                            @foreach($docsDescargables as $doc) 
                                <li>
                                @if(isset($doc->ruta_documento))
                                <a href="#" class="text-dorado" wire:click="descargarArchivo('{{ $doc->ruta_documento }}', '{{ $doc->nombre_documento}}')" title="Descargar">  {{ $doc->nombre_documento}}
                                <button type="button" class="btn-ver" title="Descargar"><img class="w-6 -mb-2" src="{{'img/iconos/ic_descargar_documentos_black.png'}}" alt="Descargar"></button>
                                </a>
                                @endif
                                </li>
                                @endforeach
                                </ul>
                        </div>
                </div>  
            </div>
         </div>
    </div>
</div>
