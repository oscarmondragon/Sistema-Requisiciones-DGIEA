<div>
    <?php
    
    use Carbon\Carbon;
    ?>
    <div x-data class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="">
                        <div>
                            <h1 class="mt-6">Revisión para  solicitud  con clave: {{$solicitud->clave_solicitud}}</h1>
                            <form x-on:submit.prevent="confirmationVoBo">
                                @csrf
                            @include('components.solicitud-ver-form')
                                
                                <div class="mt-4 sm:ml-10">
                                @if (str_contains($referer, 'vobo'))
                                    <input type="checkbox" id="vobo" name="vobo" wire:model='vobo'
                                        class="mr-1">
                                    <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del
                                        Admistrativo/Investigador.</label>
                                    @error('vobo')
                                        <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                                    @enderror
                                @endif
                                </div>

                                <div class="sm:text-right text-center mt-5">
                                @if (str_contains($referer, 'vobo'))
                                    <button type="submit" @click="confirmationVoBo()" class="btn-primary sm:w-auto w-5/6">Confirmar VoBo</button>
                                    <button type="button" class="btn-danger sm:w-auto w-5/6" @click="rechazarVoBo()">Rechazar VoBo</button>
                                    <button type="button" class="btn-warning sm:w-auto w-5/6"
                                       x-on:click="window.location.href = '{{ route('cvu.vobo') }}'">Cancelar</button>
                                       @else
                    <button type="button" class="btn-warning sm:w-auto w-5/6" x-on:click="window.location.href = '{{ route('cvu.seguimiento') }}'">Regresar</button>
                    @endif

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
            function confirmationVoBo() {
                Swal.fire({
                    customClass: {
                        title: 'swal2-title'
                    },
                    title: '¿Estás seguro de enviar tu solicitud?',
                    icon: 'warning',
                    iconColor: '#9D9361',
                    showCancelButton: true,
                    confirmButtonColor: '#62836C',
                    cancelButtonColor: '#E86562',
                    confirmButtonText: 'Si, enviar',
                    cancelButtonText: 'Cerrar',

                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('darVobo');
                    }
                });
            }

            function rechazarVoBo() {
                Swal.fire({
                        title: '¿Estás seguro que deseas rechazar el requerimiento?',
                        text: 'El requerimiento estará disponible nuevamente para edición en el perfil del emisor.',
                        icon: 'warning',
                        iconColor: '#9D9361',
                        input: "textarea",
                        inputPlaceholder: 'Motivo de rechazo',
                        customClass:{
                            input: 'textarea'
                        },
                        inputAttributes: {
                            autocapitalize: "off"
                        },
                        showCancelButton: true,
                        confirmButtonColor: '#62836C',
                        cancelButtonColor: '#E86562',
                        confirmButtonText: 'Si, aceptar',
                        cancelButtonText: 'Cerrar',
                        showLoaderOnConfirm: true,
                        inputValidator: motivoRechazo => {
                            // Si motivoRechazo es válido, debe regresar undefined. Si no, una cadena
                            if (!motivoRechazo) {
                                return "El motivo de rechazo no puede estar vacío.";
                            } else {
                                return undefined;
                            }
                        }
                    })
                    .then(resultado => {
                        if (resultado.value) {
                            motivoRechazo = resultado.value;
                            //alert("Motivo de rechazo:\n " + motivoRechazo);
                            window.livewire.emit('rechazarVobo', motivoRechazo);
                        }
                    });
            }
        </script>
    @endpush
</div>
