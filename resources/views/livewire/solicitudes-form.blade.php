<?php

use Carbon\Carbon;
?>
@include('layouts.header-cvu', ['accion' => 1])
<div x-data class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div>
                    <div>
                        <h1>Formulario solicitudes</h1>
                        @if (isset($solicitud->observaciones_vobo) || isset($solicitud->observaciones))
                            <div class="my-4">
                                <p class="bg-red-100 text-red-500 font-bold py-1 px-2 rounded-sm border border-red-500">
                                    <svg class="inline-block w-5 h-5 me-3" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                    </svg>
                                    Observaciones de rechazo:
                                    @if ($solicitud->observaciones_vobo)
                                        <span class="block pl-12 font-normal"><span class="font-bold">Por visto
                                                bueno:</span> {{ $solicitud->observaciones_vobo }}</span>
                                    @endif
                                    @if ($solicitud->observaciones)
                                        <span class="block pl-12 font-normal"><span class="font-bold">Por DGIEA: </span>
                                            {{ $solicitud->observaciones }}</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                        <form x-on:submit.prevent="saveConfirmationVoBo">
                            @csrf
                            <div>
                                <div class="mt-6">
                                    <label for="id_rubro">
                                        Rubro<samp class="text-rojo">*</samp>:
                                    </label>
                                    <select class="sm:w-auto w-full" id="id_rubro" name="id_rubro"
                                        wire:model="id_rubro" @isset($id_solicitud) disabled @endisset
                                        @change="$wire.resetearRecursos($event.target.selectedOptions[0].getAttribute('data-id-especial'))">

                                        <option value="0">Selecciona una opción</option>
                                        @foreach ($cuentasContables as $cuentaContable)
                                            <option value="{{ $cuentaContable->id }}"
                                                data-id-especial="{{ $cuentaContable->id_especial }}">
                                                {{ $cuentaContable->nombre_cuenta }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_rubro')
                                        <span class="text-rojo sm:inline-block block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-8" x-data x-init="tipoComprobacionOption = '{{ $tipo_comprobacion }}'">
                                    <label for="monto_total">Monto a solicitar<samp class="text-rojo">*</samp>: </label>
                                    <input type="number" id="monto_total" name="monto_total" wire:model="monto_total"
                                        class="inputs-formulario-solicitudes w-40" min="0"
                                        placeholder="$ 0000.00">
                                    @error('monto_total')
                                        <span class="text-rojo sm:inline-block block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-8">
                                    <label for="nombre_expedido">Expedido a nombre de: </label>
                                    <input type="text" readonly id="nombre_expedido" wire:model="nombre_expedido"
                                        class="sm:w-96 w-full
                                        disabled:bg-[#e0dddd] disabled:text-[#777171] disabled:border-[#888181] disabled:cursor-not-allowed
                                        disabled:rounded-md h-10"
                                        title="No se puede editar." placeholder="Nombre" disabled>
                                    @error('nombre_expedido')
                                        <span class="text-rojo sm:inline-block block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-8">
                                    <label for="concepto"> Concepto<samp class="text-rojo">*</samp>:</label>
                                    <input wire:model="concepto"
                                        class="inputs-formulario-solicitudes sm:w-[477px] w-full" id="concepto"
                                        type="text" placeholder="Concepto">
                                    @error('concepto')
                                        <span class="text-rojo sm:inline-block block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-8">
                                    <label for="justificacionS">Justificación<samp class="text-rojo">*</samp>:</label>
                                    <textarea wire:model="justificacionS" class="sm:w-3/4 w-full block" rows="3" cols="30" id="justificacionS"
                                        placeholder="Justificación"></textarea>
                                    @error('justificacionS')
                                        <span class="text-rojo mb-4 sm:inline-block block">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if ($id_rubro_especial == '2')
                                    <div class="mt-8">
                                        <label>Periodo</label>
                                        <p class="sm:ml-10 text-verde"><span class="font-bold">Nota: </span>Recuerda que
                                            tus requerimientos tiene un periodo de solicitud de 15 días previos.</p>
                                        <div
                                            class="mt-2 sm:ml-10 sm:grid sm:grid-cols-2 gap-4 flex-col sm:w-3/4 w-full">
                                            <div class="flex-col">
                                                <label class="block mb-1" for="finicial">Fecha inicial<samp
                                                        class="text-rojo">*</samp>:</label>
                                                <input wire:model="finicial" class="inputs-formulario" id="finicial"
                                                    type="date"
                                                    min="{{ Carbon::now()->addDay(15)->format('Y-m-d') }}">
                                                @error('finicial')
                                                    <span class="text-rojo block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="flex-col">
                                                <label class="block mb-1 sm:mt-0 mt-4" for="ffinal">Fecha
                                                    final<samp class="text-rojo">*</samp>:</label>
                                                <input wire:model="ffinal" class="inputs-formulario" id="ffinal"
                                                    type="date"
                                                    min="{{ Carbon::now()->addDay(15)->format('Y-m-d') }}">
                                                @error('ffinal')
                                                    <span class="text-rojo block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($id_rubro_especial == '3')
                                    <div class="my-5">
                                        <label for="tipo_comprobacion">Tipo de solicitud<samp
                                                class="text-rojo">*</samp>:</label>
                                        <div class="sm:ml-10 mt-2">
                                            <label class=" items-center">
                                                <input type="radio" x-model="tipoComprobacionOption"
                                                    wire:model='tipo_comprobacion' name="tipo_comprobacion"
                                                    value="vale">
                                                <span class="ml-2">Vales</span>
                                            </label>
                                            @if (Session::get('tipo_financiamiento') == 'Externo')
                                                <label class=" items-center ml-6">
                                                    <input type="radio" x-model="tipoComprobacionOption"
                                                        wire:model='tipo_comprobacion' name="tipo_comprobacion"
                                                        value="ficha">
                                                    <span class="ml-2">Ficha de gasto</span>
                                                </label>
                                            @endif
                                        </div>
                                        @error('tipo_comprobacion')
                                            <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                                @if ($id_rubro_especial == '3')
                                    <div class="mt-2">
                                        <label for="bitacoraPdfTemp">Bitacora firmada PDF<samp
                                                class="text-rojo">*</samp>:</label>
                                        <input type="file" id="bitacoraPdfTemp" wire:model='bitacoraPdfTemp'
                                            accept=".pdf">
                                    @empty($docsCartaExclusividad)
                                        <label for="bitacoraPdfTemp" class="text-dorado">Sin archivos
                                            seleccionados.</label>
                                    @endempty
                                    <br>
                                    <div wire:loading wire:target="docsbitacoraPdf">Cargando archivo...</div>

                                    @error('bitacoraPdfTemp')
                                        <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                    @enderror
                                    @error('docsbitacoraPdf')
                                        <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                    @enderror
                                    <ul>
                                        @foreach ($docsbitacoraPdf as $index => $archivo)
                                            <li>
                                                @if (isset($archivo['datos']['ruta_documento']))
                                                    <a href="#" class="text-dorado"
                                                        wire:click="descargarArchivo('{{ $archivo['datos']['ruta_documento'] }}', '{{ $archivo['datos']['nombre_documento'] }}')">
                                                        {{ $archivo['datos']['nombre_documento'] }} <button
                                                            type="button" class="btn-ver">Ver</button></a></a>
                                                @else
                                                    {{ $archivo['datos']['nombre_documento'] }}
                                                @endif
                                                <button type="button" class="btn-eliminar-lista"
                                                    @click="eliminarDocumento('docsbitacoraPdf', '{{ $index }}')">
                                                    Eliminar
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                            @endif
                        </div>
                        <div class="mt-4 sm:ml-10" x-show="tipoComprobacionOption != 'vale'">
                            <input type="checkbox" id="comprobacion" name="comprobacion"
                                wire:model='comprobacion' class="mr-1">
                            <label for="comprobacion">Me obligo a comprobar esta cantidad en un plazo no mayor a 20
                                días naturales, a partir de la
                                recepción del cheque y/o transferencia, en caso contrario autorizo a la U.A.E.M.
                                para que se descuente vía nómina<samp class="text-rojo">*</samp>.
                            </label>
                            @error('comprobacion')
                                <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4 sm:ml-10">
                            <input type="checkbox" id="aviso_privacidad" name="aviso_privacidad"
                                wire:model='aviso_privacidad' class="mr-1">
                            <label for="aviso_privacidad">Acepto aviso de privacidad simplificada de la
                                UAEMEX<samp class="text-rojo">*</samp>.</label>
                            <a wire:click="exportAviso" class="text-verde font-bold pl-2 cursor-pointer hover:underline">
                                Ver aviso de privacidad</a>
                            @error('aviso_privacidad')
                                <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-4 sm:ml-10">
                            <input type="checkbox" id="vobo" name="vobo" wire:model='vobo'
                                class="mr-1">
                            <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del
                                Admistrativo/Investigador<samp class="text-rojo">*</samp>.</label>
                            @error('vobo')
                                <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:text-right text-center mt-5">
                            @empty($solicitud)
                                <button type="button" @click="saveConfirmation()"
                                    class="btn-success sm:w-auto w-5/6">Guardar avance</button>
                            @endempty
                            <button type="submit" @click="saveConfirmationVoBo()"
                                class="btn-primary sm:w-auto w-5/6">Enviar para VoBo</button>
                            @if (str_contains($referer, 'editar') || str_contains($referer, 'crear'))
                                <button type="button" @click="cancelarSolicitud()"
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
    </div>
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
            position: 'center',
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
            title: '¿Deseas enviar tu solicitud a VoBo?',
            text: 'Una vez enviada ya no será posible modificarla.',
            position: 'center',
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

    function cancelarSolicitud() {
        Swal.fire({
            customClass: {
                confirmButton: 'swalBtnColor'
            },
            title: '¿Estás seguro que deseas cancelar?',
            text: 'Se perderán todos los datos capturados.',
            position: 'center',
            icon: 'warning',
            iconColor: '#9D9361',
            showCancelButton: true,
            confirmButtonColor: '#E86562',
            cancelButtonColor: '#62836C',
            confirmButtonText: 'Si, cancelar',
            cancelButtonText: 'Cerrar',


        }).then((result) => {
            if (result.isConfirmed) {


                var currentUrl = window.location.href;

                var reglaSeguimiento = /solicitudesS\/\d+\/editar/;
                var reglaVobo = /solicitudes\/\d+\/editar/;

                if (window.location == "{{ route('cvu.create-solicitudes') }}") {
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
                window.livewire.emit('eliminarArchivo', tipoArchivo, index);
            }
        });
    }
</script>
@endpush
</div>
