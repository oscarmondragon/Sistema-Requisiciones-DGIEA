<div>
    <div x-data class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="">
                        <div>
                            <h1 class="mt-6">Revisión para adquisición con clave:
                                <span class="text-dorado">
                                    {{ $adquisicion->clave_adquisicion }}
                                </span>
                            </h1>
                            <h2 class="text-dorado"> {{ $clave == null ? '' : 'Clave SIIA: ' . $clave }} </h2>                
                            <form x-on:submit.prevent="saveConfirmation">
                                @csrf
                                <div>
                                    <div x-data="{ open: false }">
                                        <button type="button" class="bg-blue-600 my-4" @click="open = ! open">
                                            Ver detalles
                                        </button>
                                        <div x-show="open">
                                            @include('components.adquisicion-ver-form')
                                            @can('revisor', Auth::user())
                                                <hr class="h-1 bg-verde my-8">
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="my-5"
                                        x-data = "{ ifRechazo: @entangle('estatus').defer,
                                     tipoEstatus: @entangle('tipoEstatus').defer,
                                     claveR: @entangle('clave') }">

                                        @can('revisor', Auth::user())
                                            <h2>Actualizar estado</h2>
                                            <div class="my-6">
                                                <label for="estatus">
                                                    Estado<samp class="text-rojo">*</samp>:
                                                </label>
                                                <select class="sm:w-auto w-full" id="estatus" name="estatus"
                                                    wire:model="estatus"
                                                    @change="$wire.actualizarTipoEstatus($event.target.selectedOptions[0].getAttribute('data-tipo-estatus'))">
                                                    <option value="0" data-tipo-estatus="0">Selecciona el estado
                                                    </option>
                                                    @foreach ($estatus_generales as $estatus_general)
                                                        <option value="{{ $estatus_general->id }}"
                                                            data-tipo-estatus="{{ $estatus_general->tipo }}">
                                                            {{ $estatus_general->descripcion }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('estatus')
                                                    <span class="text-rojo sm:inline-block block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endcan
                                        <div x-show="ifRechazo === '5' || ifRechazo === '9'" class="flex flex-col">
                                            <label for="observaciones_estatus" class="my-2">Observaciones<samp
                                                    class="text-rojo">*</samp>:</label>
                                            <textarea id="observaciones_estatus" name="observaciones_estatus" wire:model='observaciones_estatus'
                                                placeholder="Observaciones o motivo de rechazo" class="sm:w-3/4 w-full" rows="2" cols="30">
                                            </textarea>
                                        </div>
                                        @error('observaciones_estatus')
                                            <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                        @enderror

                                        <div x-show="(tipoEstatus == 3 || tipoEstatus == 5) && claveR == null"
                                            class="mt-6">
                                            <label for="claveSiia" class="my-2">Clave SIIA:<samp
                                                    class="text-rojo">*</samp>:</label>
                                            <input type="number" name="claveSiia" id="claveSiia" wire:model='claveSiia'
                                                placeholder="Clave SIIA" class="inputs-formulario-solicitudes w-1/4">
                                            @error('claveSiia')
                                                <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="sm:text-right text-center my-10 -mb-2">
                                        @can('revisor', Auth::user())
                                            <button type="submit" class="btn-success sm:w-auto w-5/6">Guardar</button>
                                        @endcan
                                        <button type="button" class="btn-warning sm:w-auto w-5/6"
                                            x-on:click="window.location.href = '{{ route('requerimientos.index') }}'">
                                            Regresar
                                        </button>
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
                        title: '¿Confirmar cambio?',
                        icon: 'warning',
                        iconColor: '#9D9361',
                        showCancelButton: true,
                        confirmButtonColor: '#62836C',
                        cancelButtonColor: '#E86562',
                        confirmButtonText: 'Si, confirmar',
                        cancelButtonText: 'Cerrar',

                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.livewire.emit('save');
                        }
                    });
                }
            </script>
        @endpush
    </div>
