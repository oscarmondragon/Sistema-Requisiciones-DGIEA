<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @include('layouts.header-cvu', ['accion' => 4])
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-10">
                    <div class="bg-white overflow-hidden  sm:rounded-lg">
                        @if ($docsDescargables->first())
                            <div>
                                <h1 class="mb-4">Documentos descargables</h1>
                                <ul class="sm:grid grid-cols-2 flex flex-col mt-2">
                                    @foreach ($docsDescargables as $doc)
                                        <li class="list-none inline-block items-center text-lg">
                                            <svg class="w-3.5 h-3.5 me-2 text-green-500 dark:text-green-400 inline-block"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                                            </svg>
                                            @if (isset($doc->ruta_documento))
                                                <a href="#" class="text-dorado"
                                                    wire:click="descargarArchivo('{{ $doc->ruta_documento }}', '{{ $doc->nombre_documento }}')">
                                                    {{ strtr($doc->nombre_documento, '_', ' ') }} 
                                                    <button type="button" class="btn-ver" title="Descargar">
                                                        @if ($doc->extension_documento == 'pdf')
                                                            <img class="w-6 -mb-2" src="{{ 'img/iconos/ic_pdf.png' }}"
                                                                alt="Descargar .pdf" title="Descargar .pdf">
                                                        @elseif($doc->extension_documento == 'docx')
                                                            <img class="w-6 -mb-2" src="{{ 'img/iconos/ic_docx.png' }}"
                                                                alt="Descargar .docx" title="Descargar .docx">
                                                        @elseif($doc->extension_documento == 'xlsx')
                                                            <img class="w-6 -mb-2" src="{{ 'img/iconos/ic_xlsx.png' }}"
                                                                alt="Descargar .xlsx" title="Descargar .xlsx">
                                                        @elseif($doc->extension_documento == 'pptx')
                                                            <img class="w-6 -mb-2" src="{{ 'img/iconos/ic_pptx.png' }}"
                                                                alt="Descargar .pptx" title="Descargar .pptx">
                                                        @else
                                                            <img class="w-6 -mb-2"
                                                                src="{{ 'img/iconos/ic_download.png' }}" alt="Descargar"
                                                                title="Descargar archivo">
                                                        @endif
                                                    </button>
                                                </a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <h1 class="text-center mt-10">No hay documentos para descargar</h1>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
