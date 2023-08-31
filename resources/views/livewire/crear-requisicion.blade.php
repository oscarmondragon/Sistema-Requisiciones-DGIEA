
  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <div class="inline-block relative w-64">
                  <h3>Selecciona tipo de requisicion</h3>
                <select id="tipo" wire:model= "tipo" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                  <option value="0">Selecciona un opción</option>
                  <option value="1">Solicitud de adquisición de bienes y servicios</option>
                  <option value="2">Solicitud de recursos</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
                <div> 
              </div>
              </div>
              
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

              
