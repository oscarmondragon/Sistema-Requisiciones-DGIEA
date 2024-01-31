<div>
    <?php
    
    use Carbon\Carbon;
    ?>
    <div x-data class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <div>
                            <h1 class="mt-4">Revisión para solicitud con clave: <span
                                    class="text-dorado">{{ $solicitud->clave_solicitud }}</span>
                            </h1>
                            {{-- <h2 class="text-dorado">{{ $clave == null ? '' : 'Clave SIIA: ' . $clave }}</h2> --}}
                            @isset($clave)
                                <h1>
                                    Clave SIIA: <span class="text-dorado"> {{ $clave }} </span>
                                </h1>
                            @endisset
                            <form x-on:submit.prevent="saveConfirmation">
                                @csrf
                                <div class="mt-2" x-data="{ open: false }">
                                    <button type="button" @click="open = ! open" class="bg-blue-600 my-4">Ver
                                        detalles</button>
                                    <div x-show="open">
                                        @include('components.solicitud-ver-form')
                                        @can('revisor', Auth::user())
                                            <hr class="h-1 bg-verde my-8">
                                        @endcan
                                    </div>
                                </div>
                                {{-- <div class="mt-4 sm:ml-10">
                                    @if (str_contains($referer, 'vobo'))
                                        <input type="checkbox" id="vobo" name="vobo" wire:model='vobo'
                                            class="mr-1">
                                        <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del
                                            Admistrativo/Investigador.</label>
                                        @error('vobo')
                                            <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                        @enderror
                                    @endif
                                </div> --}}
                                {{-- <div class="my-5" x-data x-init="rechazo = '{{ $estatusSolicitud }}', tipoEstatus = '{{ $tipoEstatus }}'"> --}}
                                <div
                                    x-data = "{ ifRechazo: @entangle('estatusSolicitud').defer, tipoEstatus: @entangle('tipoEstatus').defer,
                                    claveSiia: @entangle('clave').defer, checkClave: false }">
                                    @can('revisor', Auth::user())
                                        <h2>Actualizar estado</h2>
                                        <div class="my-6">
                                            <label for="estatus">
                                                Estado<samp class="text-rojo">*</samp>:
                                            </label>
                                            <select class="sm:w-auto w-full" id="estatus" name="estatus"
                                                wire:model="estatusSolicitud"
                                                @change="$wire.actualizarTipoEstatus($event.target.selectedOptions[0].getAttribute('data-tipo-estatus'))">
                                                <option value="0" data-tipo-estatus="0">Selecciona el estado</option>
                                                @foreach ($estatus_solicitudes as $estatus)
                                                    <option value="{{ $estatus->id }}"
                                                        data-tipo-estatus="{{ $estatus->tipo }}">
                                                        {{ $estatus->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('estatusSolicitud')
                                                <span class="text-rojo sm:inline-block block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endcan
                                    <div x-show="ifRechazo === '5' || ifRechazo === '12' || ifRechazo === '14'"
                                        class="flex flex-col mb-6">
                                        <label for="observaciones_estatus" class="my-2">
                                            Observaciones
                                            <samp class="text-rojo">*</samp>
                                            :</label>
                                        <textarea id="observaciones_estatus" name="observaciones_estatus" wire:model='observaciones_estatus'
                                            placeholder="Observaciones o motivo de rechazo" class="sm:w-3/4 w-full" rows="2" cols="30">
                                        </textarea>
                                        @error('observaciones_estatus')
                                            <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div x-show="(tipoEstatus === '4' || tipoEstatus === '5') || claveSiia != null">
                                        <label for="sClaveSiia">Clave SIIA<samp class="text-rojo">*</samp>:</label>
                                        <input type="text" name="sClaveSiia" id="sClaveSiia" wire:model='sClaveSiia'
                                            placeholder="Clave SIIA" maxlength="16"
                                            :disabled="claveSiia != null && checkClave == false"
                                            class="inputs-formulario-solicitudes sm:w-1/4 w-full">
                                        @if ($clave != null)
                                            <div class="inline-block ml-5">
                                                <input type="checkbox" name="editarClave" id="editarClave"
                                                    x-model="checkClave" value="true" @click="editarClaveSiia()">
                                                <label for="editarClave">Editar clave SIIA</label>
                                            </div>
                                        @endif

                                        @error('sClaveSiia')
                                            <span class="my-2 text-rojo block">{{ $message }}</span>
                                        @enderror

                                        <h1 x-text="checkClave"></h1>
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
                            </form>
                        </div>
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
                    title: '¿Guardar cambios?',
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

            function editarClaveSiia() {
                //alert(editarClave);
                if (document.getElementById('editarClave').checked != false) {
                    Swal.fire({
                        customClass: {
                            title: 'swal2-title'
                        },
                        title: '¿Estas seguro que deseas editar la clave SIIA?',
                        text: '123ASD.',
                        icon: 'warning',
                        iconColor: '#9D9361',
                        showCancelButton: true,
                        confirmButtonColor: '#62836C',
                        cancelButtonColor: '#E86562',
                        confirmButtonText: 'Si, editar',
                        cancelButtonText: 'Cerrar',

                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("sClaveSiia").disabled = false;
                        } else {
                            //var input = document.getElementById('editarClave');
                            document.getElementById("sClaveSiia").disabled = true;
                            document.getElementById('editarClave').checked = false;
                            document.getElementById('editarClave').value = false;
                        }
                    });
                } else {
                    document.getElementById("sClaveSiia").disabled = true;
                }
            }
        </script>
    @endpush
</div>