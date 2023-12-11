<div>
    {{-- Do your work, then step back. --}}
@include('layouts.header-cvu', ['accion' => 2])
<div x-data class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <div class="">
            <div>
              <h1 class="mt-6">Visto bueno para adquisición  con clave: {{$adquisicion->clave_adquisicion}}</h1>
              <form x-on:submit.prevent="confirmationVoBo">
                @csrf
                <div>
                  @include('components.adquisicion-ver-form')
                  @if (str_contains($referer, 'vobo'))
                  <div class="mt-10">
                    <input type="checkbox" id="vobo" wire:model='vobo' name="vobo" class="rounded-full sm:ml-10 mr-2">
                    <label for="vobo">Dar mi visto bueno a este requerimiento.</label>
                    @error('vobo') <span class=" text-rojo error">{{ $message }}</span> @enderror
                </div>
                    
                  @endif
                  
                <div class="sm:text-right text-center my-10 -mb-2">
                @if (str_contains($referer, 'vobo'))
                    <button type="submit" class="btn-primary sm:w-auto w-5/6" @click="confirmationVoBo()">Confirmar VoBo</button>
                    <button type="button" class="btn-danger sm:w-auto w-5/6" @click="rechazarVoBo()">Rechazar VoBo</button>
                    <button type="button" class="btn-warning sm:w-auto w-5/6" x-on:click="window.location.href = '{{ route('cvu.vobo') }}'">Cancelar</button>
                    @else
                    <button type="button" class="btn-warning sm:w-auto w-5/6" x-on:click="window.location.href = '{{ route('cvu.seguimiento') }}'">Regresar</button>
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
            function confirmationVoBo() {
                Swal.fire({
                    customClass: {
                        title: 'swal2-title'
                    },
                    title: '¿Confirmar VoBo?',
                    icon: 'warning',
                    iconColor: '#9D9361',
                    showCancelButton: true,
                    confirmButtonColor: '#62836C',
                    cancelButtonColor: '#E86562',
                    confirmButtonText: 'Si, confirmar',
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
                        backdrop: true,
                        inputPlaceholder: 'Motivo de rechazo',
                        customClass:{
                          input: 'textarea'
                        },
                        //html: `<textarea class="w-full" rows="3" cols="30" placeholder="Motivo de rechazo"></textarea>`,
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
                            motivo = resultado.value;
                            //alert("Motivo de rechazo:\n " + motivo);
                            window.livewire.emit('rechazarVobo', motivo);
                        }
                    });
            }
        </script>
    @endpush
  </div>

