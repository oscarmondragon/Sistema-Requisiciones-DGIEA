@include('layouts.header-cvu', ['accion' => 1])
<div x-data class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div>
                    <div class="mb-6">
                        <h1>Formulario adquisición de bienes y servicios</h1>
                        @if (isset($adquisicion->observaciones_vobo) || isset($adquisicion->observaciones))
                            <div class="my-4">
                                <p class="bg-red-100 text-red-500 font-bold py-1 px-2 rounded-sm border border-red-500">
                                    <svg class="inline-block w-5 h-5 me-3" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                    </svg>
                                    Observaciones de rechazo:
                                    @if ($adquisicion->observaciones_vobo)
                                        <span class="block pl-12 font-normal"><span class="font-bold">Por visto
                                                bueno:</span> {{ $adquisicion->observaciones_vobo }}</span>
                                    @endif
                                    @if ($adquisicion->observaciones)
                                        <span class="block pl-12 font-normal"><span class="font-bold">Por DGIEA: </span>
                                            {{ $adquisicion->observaciones }}</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                        <form x-on:submit.prevent="saveConfirmationVoBo">
                            @csrf
                            <div>
                                <div class="my-6">
                                    <label for="id_rubro">
                                        Rubro<samp class="text-rojo">*</samp>:
                                    </label>
                                    @if (str_contains($referer, 'vobo') || str_contains($referer, 'seguimiento'))
                                        <select class="w-auto bg-red-500" id="id_rubro" name="id_rubro" wire:model="id_rubro"
                                            disabled>
                                        @else
                                            <select class="sm:w-auto w-full" required id="id_rubro" name="id_rubro"
                                                wire:model="id_rubro" :disabled="{{$id_adquisicion != 0}}"
                                                @change="$wire.resetearBienes($event.target.selectedOptions[0].getAttribute('data-id-especial'))">
                                    @endif

                                    <option value="0">Selecciona una opción</option>
                                    @foreach ($cuentasContables as $cuentaContable)
                                        <option value="{{ $cuentaContable->id }}"
                                            data-id-especial="{{ $cuentaContable->id_especial }}">
                                            {{ $cuentaContable->nombre_cuenta }}</option>
                                    @endforeach
                                    </select>
                                    @error('id_rubro')
                                        <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label>
                                        Descripción del bien o servicio<samp class="text-rojo">*</samp>:
                                    </label>
                                    @if ($id_rubro != 0)
                                        <button type="button"
                                            x-on:click="$wire.emit('openModal', 'adquisicion-description-modal', { 'id_rubro': {{ $id_rubro }}, 'id_rubro_especial': {{ $id_rubro_especial ?: 'null' }} })"
                                            class="bg-verde w-8 h-8 py-0 px-0 rounded-full hover:bg-[#3c5042] focus:ring-2 focus:outline-none focus:ring-[#3c5042]">
                                            <span class="text-white font-extrabold text-2xl">+</span>
                                        </button>
                                    @else
                                        <p class="bg-gray-300 w-8 h-8 -pt-2 px-2 ml-1 rounded-full hover:bg-gray-200 hover:font-extrabold hover:text-gray-400 cursor-not-allowed inline-block select-none"
                                            disabled>
                                            <span title="Primero selecciona un rubro."
                                                class="text-white font-extrabold text-2xl">+</span>
                                        </p>
                                    @endif
                                    @error('bienes')
                                        <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                    @enderror

                                </div>
                                <div class="overflow-x-auto" wire:poll x-data="{ elementos: @entangle('bienes').defer, id_rubro: '{{ $id_rubro }}' }">
                                    <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto"
                                        x-show="elementos.length > 0">
                                        <thead>
                                            <tr class="bg-blanco">
                                                <th class="w-[26px]">#</th>
                                                <th class="w-[200px]">Descripción</th>
                                                <th class="w-[80px]">Cantidad</th>
                                                <th class="w-[80px]">Precio Unitario</th>
                                                <th class="w-[80px]">IVA</th>
                                                <th class="w-[80px]">Importe</th>
                                                @if ($id_rubro_especial == '1')
                                                    <th class="w-[300px]">Justificación</th>
                                                    <th class="w-[180px]">Beneficiados</th>
                                                @endif
                                                <th class="w-[148px]">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(elemento, index) in elementos" :key="index">
                                                <tr class="border border-b-gray-200 border-transparent">
                                                    <th class="w-[26px]" x-text="index + 1"></th>
                                                    <th class="w-[200px]"
                                                        x-text="elemento.descripcion.length > 85 ? elemento.descripcion.substring(0,85) + '...' : elemento.descripcion">
                                                    </th>
                                                    <th class="w-[80px]" x-text="elemento.cantidad"></th>
                                                    <th class="w-[80px]" x-text="elemento.precio_unitario"></th>
                                                    <th class="w-[80px]" x-text="elemento.iva"></th>
                                                    <th class="w-[80px]" x-text="elemento.importe"></th>
                                                    @if ($id_rubro_especial == '1')
                                                        <th class="w-[300px]"
                                                            x-text="elemento.justificacion_software.length > 85 ? elemento.justificacion_software.substring(0,85) + '...' : elemento.justificacion_software">
                                                        </th>
                                                        <th class="w-[180px]"
                                                            x-html="'Alumnos: ' + elemento.alumnos +
                            '<br>Profesores: ' + elemento.profesores_invest +
                            '<br>Administrativos: ' + elemento.administrativos">
                                                        </th>
                                                    @endif
                                                    <th class="w-[148px]">
                                                        <button type="button"
                                                            @click='$wire.emit("openModal", "adquisicion-description-modal",
                        { _id: elemento._id, descripcion: elemento.descripcion, cantidad: elemento.cantidad, precio_unitario: elemento.precio_unitario, iva: elemento.iva, checkIva: elemento.checkIva == 1 ? elemento.checkIva : 0, importe: elemento.importe, justificacion_software: elemento.justificacion_software,
                          alumnos: elemento.alumnos, profesores_invest: elemento.profesores_invest, administrativos: elemento.administrativos, id_rubro: id_rubro,
                          id_rubro_especial: {{ $id_rubro_especial ?: 'null' }} })'
                                                            class="btn-tablas">
                                                            <img src="{{ '/img/botones/btn_editar.png' }}"
                                                                alt="Image/png" title="Editar">
                                                        </button>
                                                        <button type="button"
                                                            @click.stop="elementos.splice(index, 1); $wire.deleteBien(elemento)"
                                                            class="btn-tablas">
                                                            <img src="{{ '/img/botones/btn_eliminar.png' }}"
                                                                alt="Image/png" title="Eliminar">
                                                        </button>
                                                    </th>
                                                </tr>
                                            </template>
                                            <tr>
                                                @if ($id_rubro_especial == '1')
                                                    <th></th>
                                                    <th></th>
                                                @endif
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Subtotal</th>
                                                <th>${{ $subtotal }}</th>
                                            </tr>
                                            <tr>
                                                @if ($id_rubro_especial == '1')
                                                    <th></th>
                                                    <th></th>
                                                @endif
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>IVA</th>
                                                <th>${{ $iva }}</th>
                                            </tr>

                                            <tr class="border border-b-gray-200 border-transparent">
                                                @if ($id_rubro_especial == '1')
                                                    <th></th>
                                                    <th></th>
                                                @endif
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Total</th>
                                                <th>${{ $total }}</th>
                                            </tr>

                                        </tbody>
                                    </table>

                                </div>
                                <div class="my-5" x-data x-init="afectaSelectedOption = '{{ $afecta_investigacion }}'">
                                    <label for="afecta" class="text-dorado font-bold">
                                        ¿El cambio de alguna de las características del bien descritas en la cotización,
                                        afectan el desarrollo de la investigación?
                                    </label>
                                    <div class="mt-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" x-model="afectaSelectedOption"
                                                wire:model='afecta_investigacion' name="siAfecta" value="1">
                                            <span class="ml-2">Si</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6">
                                            <input type="radio" x-model="afectaSelectedOption"
                                                wire:model='afecta_investigacion'
                                                wire:click="resetJustificacionAcademica" name="noAfecta"
                                                value="0" checked>
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>

                                    <div x-show="afectaSelectedOption === '1'" class="flex flex-col">
                                        <label for="justificacion" class="my-2">Justificación académica<samp
                                                class="text-rojo">*</samp>:</label>
                                        <textarea id="justificacion" name="justificacion" wire:model='justificacion_academica' placeholder="Justificación"
                                            class="sm:w-3/4 w-full" rows="2" cols="30">
              </textarea>
                                    </div>
                                    @error('justificacion_academica')
                                        <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-2" x-data x-init="exclusividadSelectedOption = '{{ $exclusividad }}'">
                                <label for="afecta">
                                    ¿Es bien o servicio con exclusividad?
                                </label>
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" x-model="exclusividadSelectedOption"
                                            wire:model='exclusividad' name="siExclusivo" value="1">
                                        <span class="ml-2">Si</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="radio" x-model="exclusividadSelectedOption"
                                            wire:model='exclusividad'
                                            wire:click="resetdocsCartaExclusividad({{ isset($adquisicion->id) ? $adquisicion->id : 0 }})"
                                            name="noExclusivo" value="0" checked>
                                        <span class="ml-2">No</span>
                                    </label>
                                </div>
                                <div x-show="exclusividadSelectedOption === '1'">
                                    <label for="cartaExclusividadTemp">Carta de exclusividad<samp
                                            class="text-rojo">*</samp>:</label>
                                    <input type="file" id="cartaExclusividadTemp"
                                        wire:model='cartaExclusividadTemp' accept=".pdf">
                                    @empty($docsCartaExclusividad)
                                        <label for="cartaExclusividadTemp" class="text-dorado">Sin archivos
                                            seleccionados.</label>
                                    @endempty
                                    <br>
                                    <div wire:loading wire:target="docsCartaExclusividad">Cargando archivo...</div>
                                    @error('cartaExclusividadTemp')
                                        <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                    @enderror
                                    @error('docsCartaExclusividad')
                                        <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                    @enderror
                                    <ul>
                                        @foreach ($docsCartaExclusividad as $index => $docCarta)
                                            <li>
                                                @if (isset($docCarta['datos']['ruta_documento']))
                                                    <a href="#" class="text-dorado"
                                                        wire:click="descargarArchivo('{{ $docCarta['datos']['ruta_documento'] }}', '{{ $docCarta['datos']['nombre_documento'] }}')">
                                                        {{ $docCarta['datos']['nombre_documento'] }} <button
                                                            type="button" class="btn-ver">Ver</button></a>
                                                @else
                                                    {{ $docCarta['datos']['nombre_documento'] }}
                                                @endif
                                                {{-- <button type="button" class="btn-eliminar-lista" wire:click="eliminarArchivo('cartasExclusividad', {{ $index }})"> --}}
                                                <button type="button" class="btn-eliminar-lista"
                                                    @click="eliminarDocumento('cartasExclusividad', '{{ $index }}')">
                                                    Eliminar
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="mt-2">
                                    <label for="cotizacionFirmadaTemp">Cotización PDF firmada<samp
                                            class="text-rojo">*</samp>:</label>
                                    <input type="file" id="cotizacionFirmadaTemp"
                                        wire:model='cotizacionFirmadaTemp' accept=".pdf">
                                    @empty($docsCotizacionesFirmadas)
                                        <label for="cotizacionFirmadaTemp" class="text-dorado">Sin archivos
                                            seleccionados.</label>
                                    @endempty
                                    <br>
                                    <div wire:loading wire:target="docsCotizacionesFirmadas">Cargando archivo...</div>
                                </div>
                                @error('cotizacionFirmadaTemp')
                                    <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                @enderror
                                @error('docsCotizacionesFirmadas')
                                    <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                @enderror
                                <ul>
                                    @foreach ($docsCotizacionesFirmadas as $index => $docFirmadas)
                                        <li>
                                            @if (isset($docFirmadas['datos']['ruta_documento']))
                                                <a href="#" class="text-dorado"
                                                    wire:click="descargarArchivo('{{ $docFirmadas['datos']['ruta_documento'] }}', '{{ $docFirmadas['datos']['nombre_documento'] }}')">
                                                    {{ $docFirmadas['datos']['nombre_documento'] }} <button
                                                        type="button" class="btn-ver">Ver</button></a>
                                            @else
                                                {{ $docFirmadas['datos']['nombre_documento'] }}
                                            @endif
                                            <button type="button" class="btn-eliminar-lista"
                                                @click="eliminarDocumento('cotizacionesFirmadas', '{{ $index }}')">
                                                Eliminar
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-2">
                                    <label for="cotizacionPdfTemp">Cotizaciones PDF (Pueden ir o no firmadas)<samp
                                            class="text-rojo">*</samp>:</label>
                                    <input type="file" id="cotizacionPdfTemp" wire:model='cotizacionPdfTemp'
                                        accept=".pdf">
                                    @empty($docsCotizacionesPdf)
                                        <label for="cotizacionPdfTemp" class="text-dorado">Sin archivos
                                            seleccionados.</label>
                                    @endempty
                                    <br>
                                    <div wire:loading wire:target="docsCotizacionesPdf">Cargando archivo...</div>
                                </div>
                                @error('cotizacionPdfTemp')
                                    <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                @enderror
                                @error('docsCotizacionesPdf')
                                    <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                @enderror
                                <ul class="my-2">
                                    @foreach ($docsCotizacionesPdf as $index => $docPdf)
                                        <li>
                                            @if (isset($docPdf['datos']['ruta_documento']))
                                                <a href="#" class="text-dorado"
                                                    wire:click="descargarArchivo('{{ $docPdf['datos']['ruta_documento'] }}', '{{ $docPdf['datos']['nombre_documento'] }}')">
                                                    {{ $docPdf['datos']['nombre_documento'] }} <button type="button"
                                                        class="btn-ver">Ver</button></a>
                                            @else
                                                {{ $docPdf['datos']['nombre_documento'] }}
                                            @endif
                                            <button type="button" class="btn-eliminar-lista"
                                                @click="eliminarDocumento('cotizacionesPdf', '{{ $index }}')">
                                                Eliminar
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div>
                                <label x-show="exclusividadSelectedOption === '1'" for="anexoDocumentos"
                                    class="text-verde mt-5 block">
                                    <span class="font-bold">Nota: </span>Adjunte aquí el soporte de exclusividad.
                                </label>
                                <label for="anexoOtroTemp">Anexo técnico u otros documentos:</label>
                                <input type="file" id="anexoOtroTemp" wire:model='anexoOtroTemp' accept=".pdf">
                                @empty($docsAnexoOtrosDocumentos)
                                    <label for="anexoOtroTemp" class="text-dorado">Sin archivos seleccionados.</label>
                                @endempty
                                <br>
                                <div wire:loading wire:target="docsAnexoOtrosDocumentos">Cargando archivo...</div>
                                @error('anexoOtroTemp')
                                    <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                @enderror
                                @error('docsAnexoOtrosDocumentos')
                                    <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                @enderror
                                <ul>
                                    @foreach ($docsAnexoOtrosDocumentos as $index => $anexoDoc)
                                        <li>
                                            @if (isset($anexoDoc['datos']['ruta_documento']))
                                                <a href="#" class="text-dorado"
                                                    wire:click="descargarArchivo('{{ $anexoDoc['datos']['ruta_documento'] }}', '{{ $anexoDoc['datos']['nombre_documento'] }}')">
                                                    {{ $anexoDoc['datos']['nombre_documento'] }} <button
                                                        type="button" class="btn-ver">Ver</button></a>
                                            @else
                                                {{ $anexoDoc['datos']['nombre_documento'] }}
                                            @endif
                                            <button type="button" class="btn-eliminar-lista"
                                                @click="eliminarDocumento('anexoDocumentos', '{{ $index }}')">
                                                Eliminar
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                    </div>
                    <p class="text-verde mt-5"> <span class="font-bold">Nota:</span> Las cotizaciones deben describir
                        exactamente el mismo material, suministro, servicio general,
                        bien mueble o intangible.
                    </p>
                    <div class="mt-10">
                        <input type="checkbox" id="vobo" wire:model='vobo' name="vobo"
                            class="rounded-full sm:ml-10">
                        <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del
                            Admistrativo/Investigador<samp class="text-rojo">*</samp></label>
                        @error('vobo')
                            <span class=" text-rojo error sm:inline-block block">{{ $message }}</span>
                        @enderror
                        <div class="sm:text-right text-center my-10 -mb-2">

                            @empty($id_adquisicion)
                                <button type="button" @click="saveConfirmation()"
                                    class="btn-success sm:w-auto w-5/6">Guardar avance</button>
                            @endempty
                            <button type="submit" @click="saveConfirmationVoBo()"
                                class="btn-primary sm:w-auto w-5/6">Enviar para VoBo</button>
                            @if (str_contains($referer, 'editar') || str_contains($referer, 'crear'))
                                <button type="button" @click="cancelarAdquisicion()"
                                    class="btn-warning sm:w-auto w-5/6">Cancelar</button>
                            @else
                                <button type="button" class="btn-warning sm:w-auto w-5/6"
                                    x-on:click="window.location.href = '{{ route('cvu.seguimiento') }}'">Regresar</button>
                            @endif
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    function saveConfirmation() {
                        Swal.fire({
                            customClass: {
                                title: 'swal2-title'
                            },
                            title: '¿Solo deseas guardar el avance?',
                            text: 'Recuerda que solo sera visible para ti. Deberás completarlo y enviarlo a VoBo posteriormente.',
                            icon: 'warning',
                            iconColor: '#9D9361',
                            showCancelButton: true,
                            confirmButtonColor: '#62836C',
                            cancelButtonColor: '#E86562',
                            confirmButtonText: 'Si, guardar',
                            cancelButtonText: 'Cerrar',

                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.livewire.emit('save');
                            }
                        });
                    }

                    function saveConfirmationVoBo() {
                        Swal.fire({
                            customClass: {
                                title: 'swal2-title'
                            },
                            title: '¿Deseas enviar tu adquisición a VoBo?',
                            text: 'Una vez enviada ya no será posible modificarla.',
                            icon: 'warning',
                            iconColor: '#9D9361',
                            showCancelButton: true,
                            confirmButtonColor: '#62836C',
                            cancelButtonColor: '#E86562',
                            confirmButtonText: 'Si, enviar',
                            cancelButtonText: 'Cerrar',

                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.livewire.emit('saveVobo');
                            }
                        });
                    }

                    function cancelarAdquisicion() {
                        Swal.fire({
                            customClass: {
                                title: 'swal2-title'
                            },
                            title: '¿Estás seguro que deseas cancelar?',
                            text: 'Se perderán todos los datos capturados.',
                            icon: 'warning',
                            iconColor: '#9D9361',
                            showCancelButton: true,
                            confirmButtonColor: '#E86562',
                            cancelButtonColor: '#62836C',
                            confirmButtonText: 'Si, cancelar',
                            cancelButtonText: 'Cerrar',

                        }).then((result) => {
                            if (result.isConfirmed) {
                                //alert(window.location + "\n" + "{{ route('cvu.create-adquisiciones') }}");

                                var currentUrl = window.location.href;

                                var reglaSeguimiento = /adquisicionesS\/\d+\/editar/;
                                var reglaVobo = /adquisiciones\/\d+\/editar/;

                                if (window.location == "{{ route('cvu.create-adquisiciones') }}") {
                                    window.location.href = '{{ route('cvu.create') }}';

                                } else if (currentUrl.match(reglaSeguimiento)) {
                                    window.location.href = '{{ route('cvu.seguimiento') }}';
                                } else if (currentUrl.match(reglaVobo)) {
                                    window.location.href = '{{ route('cvu.vobo') }}';
                                } else {
                                    window.location.href = '{{ route('cvu.vobo') }}';
                                }

                            }
                        });
                    }

                    function eliminarDocumento(tipoArchivo, index) {
                        Swal.fire({
                            customClass: {
                                title: 'swal2-title'
                            },
                            title: '¿Estás seguro que deseas eliminar el documento?',
                            icon: 'warning',
                            iconColor: '#9D9361',
                            showCancelButton: true,
                            confirmButtonColor: '#E86562',
                            cancelButtonColor: '#62836C',
                            confirmButtonText: 'Si, eliminar',
                            cancelButtonText: 'Cerrar',

                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.livewire.emit('eliminarArchivo', tipoArchivo, index);
                            }
                        });
                    }
                </script>
            @endpush
        </div>
