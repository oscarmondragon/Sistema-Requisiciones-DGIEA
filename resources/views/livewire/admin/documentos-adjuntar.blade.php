<div x-data class="sm:py-6 my-3">
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight ml-4">
            {{ __('Documentos descargables') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-textos_generales">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                    @if (session('success'))
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                text: '{{ session('success') }}',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#62836C',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        </script>
                    @endif

                    @if (session('error'))
                        <div
                            class="bg-red-100 text-red-500 font-bold py-1 px-2 rounded-sm border-l-4 border-red-500 sm:mt-0 mt-4 mb-10">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div x-data="{ open: false }">
                        <div class="flex items-center">
                            <h1 class="text-dorado">Nuevo documento:</h1>
                            <button class="bg-verde rounded-full hover:bg-[#4e6856] hover:rounded-full"
                                @click="open = ! open">+</button>
                        </div>

                        <div x-show="open">
                            <form x-on:submit.prevent="submit">
                                <div class="flex sm:flex-row flex-col mt-4">
                                    <div class="flex-1 sm:w-32 w-full">
                                        <label class="block mb-2" for="nombreDocumento">
                                            Nombre del documento<span class="text-rojo">*</span>:
                                        </label>
                                        <input type="text" name="nombreDocumento" id="nombreDocumento"
                                            wire:model="nombreDocumento"
                                            title="Es el nombre que aparecera en el listado."
                                            class="inputs-formulario-solicitudes sm:w-96 w-full"
                                            placeholder="Nombre del documento">
                                        @error('nombreDocumento')
                                            <span class=" text-rojo block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="flex-1 sm:w-64 w-full sm:mt-0 mt-4">
                                        <label class="block" for="documento">Documento<span
                                                class="text-rojo">*</span>:</label>
                                        <input type="file" name="documento" id="documento" wire:model="documento">
                                        <div wire:loading wire:target="documento">Cargando archivo...</div>

                                        @isset($documento)
                                            {{ $documento->getClientOriginalName() }}
                                        @endisset

                                        @error('documento')
                                            <span class=" text-rojo block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="flex-none sm:text-start text-end sm:my-auto">
                                        <button type="submit" wire:click="store()" class="btn-success">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if ($docsDescargables->first())
                        <div class="mt-8">
                            <h1>Documentos guardados</h1>
                            <ul class="sm:grid grid-cols-2 mt-4">
                                @foreach ($docsDescargables as $doc)
                                    <li class="list-none inline-block items-center text-lg">
                                        <svg class="w-3.5 h-3.5 me-2 text-green-500 dark:text-green-400 inline-block"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                                        </svg>
                                        @if (isset($doc->ruta_documento))
                                            <a href="#" class="text-dorado"
                                                wire:click="descargarArchivo('{{ $doc->ruta_documento }}', '{{ $doc->nombre_documento }}')">
                                                {{ strtr($doc->nombre_documento, '_', ' ') }}
                                                <button type="button" class="btn-ver">
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
                                                        <img class="w-6 -mb-2" src="{{ 'img/iconos/ic_download.png' }}"
                                                            alt="Descargar" title="Descargar archivo">
                                                    @endif
                                                </button>
                                            </a>
                                            <a href="#" class="ml-4">
                                                <button class="btn-tablas" title="Eliminar documento.">
                                                    <img src="{{ 'img/botones/btn_eliminar.png' }}"
                                                        alt="Botón eliminar" class="w-6 -mb-2"
                                                        @click="eliminarDocumento('{{ $doc->ruta_documento }}', '{{ $doc->nombre_documento }}')">
                                                </button>
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function eliminarDocumento(ruta, nombreDoc) {
                Swal.fire({
                    customClass: {
                        title: 'swal2-title'
                    },
                    title: '¿Estás seguro que deseas eliminar el documento ' + nombreDoc.replaceAll("_", " ") + '?',
                    position: 'center',
                    icon: 'warning',
                    iconColor: '#9D9361',
                    showCancelButton: true,
                    confirmButtonColor: '#E86562',
                    cancelButtonColor: '#62836C',
                    confirmButtonText: 'Si, eliminar',
                    cancelButtonText: 'Cerrar',

                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('eliminarArchivo', ruta, nombreDoc);
                    }
                });
            }
        </script>
    @endpush
</div>
