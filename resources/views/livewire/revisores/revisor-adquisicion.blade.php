<div>
    <div x-data class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="">
                <div>
                  <h1 class="mt-6">Revisión para  adquisición  con clave: {{$adquisicion->clave_adquisicion}}</h1>
                  <form x-on:submit.prevent="saveConfirmation">
                    @csrf
                    <div>
                      @include('components.adquisicion-ver-form')
                     <hr>
                    <div class="my-5" x-data x-init="ifRechazo = '{{ $estatus }}'">
                      <h2>Actualizar estado</h2>
                      <div class="my-6">
                        <label for="id_rubro">
                        Estado<samp class="text-rojo">*</samp>:
                        </label>
                        <select class="sm:w-auto w-full" required id="estatus" name="estatus" wire:model="estatus">
                        @foreach ($estatus_generales as $estatus_generales)
                        <option value="{{ $estatus_generales->id }}">{{ $estatus_generales->descripcion }}</option>
                        @endforeach
                        </select>
                    </div>
                      <div x-show="ifRechazo === '5'" class="flex flex-col">
                        <label for="observaciones_estatus" class="my-2">Observaciones<samp class="text-rojo">*</samp>:</label>
                        <textarea id="observaciones_estatus" name="observaciones_estatus" wire:model='observaciones_estatus' placeholder="Observaciones o motivo de rechazo" class="sm:w-3/4 w-full" rows="2" cols="30">
                  </textarea>
                      </div>
                      @error('observaciones_estatus') <span class=" text-rojo sm:inline-block block">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:text-right text-center my-10 -mb-2">
                   
                         <button type="submit"  class="btn-success sm:w-auto w-5/6">Guardar</button>
                        <button type="button" class="btn-warning sm:w-auto w-5/6" x-on:click="window.location.href = '{{ route('requerimientos.index') }}'">Regresar</button>
                     
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
