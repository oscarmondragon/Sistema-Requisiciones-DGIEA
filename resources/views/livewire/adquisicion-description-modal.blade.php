<x-modal>
    <x-slot name="title">
        Descripcion del bien o servicio
    </x-slot>
    <x-slot name="content">
        <div class="w-full max-w-xs">
              <div class="mb-4">
                <input type="hidden" wire:model="_id">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
                  Descripción
                </label>
                <input wire:model= "descripcion" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="descripcion" type="text" placeholder="Descripción">
                @error('descripcion') <span class="error">{{ $message }}</span> @enderror
              </div>
              <div class="mb-4">
                <label   class="block text-gray-700 text-sm font-bold mb-2" for="importe">
                  Importe
                </label>
                <input  wire:model= "importe" required  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="importe" type="number" placeholder="Importe">
                @error('importe') <span class="error">{{ $message }}</span> @enderror
              </div> 
              @if ($rubro === '3')
              <div class="mb-4">
                <label   class="block text-gray-700 text-sm font-bold mb-2" for="importe">
                  Descripción y justificación al cual será dedicado el software:
                </label>
                <input wire:model= "justificacionSoftware" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="justificacionSoftware" type="text" placeholder="Descripción y justificación">
                @error('justificacionSoftware') <span class="error">{{ $message }}</span> @enderror
              </div> 

              <div class="mb-4">
                <label   class="block text-gray-700 text-sm font-bold mb-2" for="importe">
                  Selecciona los usuarios y escribe cuantos de ellos serán beneficiados
                </label>
                <label   class="block text-gray-700 text-sm font-bold mb-2" for="importe">
                  Alumnos:
                </label>
                <input wire:model= "numAlumnos" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numAlumnos" type="number" placeholder="">
                @error('numAlumnos') <span class="error">{{ $message }}</span> @enderror
              
              <label   class="block text-gray-700 text-sm font-bold mb-2" for="importe">
                Profesores / Investigadores:
              </label>
              <input wire:model= "numProfesores" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numProfesores" type="number" placeholder="">
              @error('numProfesores') <span class="error">{{ $message }}</span> @enderror
           
            <label   class="block text-gray-700 text-sm font-bold mb-2" for="importe">
              Administrativos:
            </label>
            <input wire:model= "numAdministrativos" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numAdministrativos" type="number" placeholder="">
            @error('numAdministrativos') <span class="error">{{ $message }}</span> @enderror
          </div> 
          @endif
        </div> 
             
    </x-slot>

    <x-slot name="buttons">
        <button wire:click="$emit('closeModal')" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">Cancelar</button>
        <button wire:click="agregarElemento({{ $_id}}, {{$rubro}})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
    </x-slot>
</x-modal>