<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 text-gray-900">
        <div class="inline-block relative">
          <h3>Seleccione el tipo de requerimiento</h3>
          <select id="tipo" wire:model="tipo" class="mt-2">
             <option value="0">Selecciona un opción</option>
                  @foreach ($tiposRequisicion as $tipoRequisicion)
                      <option value="{{ $tipoRequisicion->id }}">{{ $tipoRequisicion->descripcion }}</option>
                  @endforeach
          </select>
          <!-- <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div> -->
          <div>
          </div>
        </div>
        
         
        @if(session('success'))
          <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium"> {{ session('success') }}</span>
          </div>
        @endif

        @if ($tipo == 1)

        <livewire:adquisiciones-form />
        
        @endif
        @if ($tipo == 2)

        <livewire:solicitudes-form />

        @endif
      </div>
    </div>
  </div>
</div>