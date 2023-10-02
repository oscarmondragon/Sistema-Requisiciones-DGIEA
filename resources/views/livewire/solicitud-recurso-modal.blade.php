<x-modal>
    <x-slot name="title">
        Descripcion del recurso
    </x-slot>
    <x-slot name="content">
        <div class="w-full max-w-xs">
              <div class="mb-4">
                <input type="hidden" wire:model="_id">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="importe">
                  Importe
                </label>
                <input wire:model= "importe" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="importe" type="float" placeholder="Importe">
                @error('importe') <span class="error">{{ $message }}</span> @enderror
              </div>
              <div class="mb-4">
                <label   class="block text-gray-700 text-sm font-bold mb-2" for="concepto">
                  Concepto
                </label>
                <input  wire:model= "concepto" required  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="concepto" type="text" placeholder="Concepto">
                @error('concepto') <span class="error">{{ $message }}</span> @enderror
              </div> 
              <div class="mb-4">
                <label   class="block text-gray-700 text-sm font-bold mb-2" for="justificacionS">
                  Justificación
                </label>
                <input wire:model= "justificacionS" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="justificacionS" type="text" placeholder="Justificación">
                @error('justificacionS') <span class="error">{{ $message }}</span> @enderror
              </div> 
              @if ($id_rubro_especial == '2')

                <div class="mb-4">
                  <label   class="block text-gray-700 text-sm font-bold mb-2" for="finicial">
                  Fecha inicial:
                  </label>
                  <input wire:model= "finicial" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="finicial" type="date" placeholder="">
                  @error('finicial') <span class="error">{{ $message }}</span> @enderror
               
                  <label   class="block text-gray-700 text-sm font-bold mb-2" for="ffinal">
                  Fecha final:
                  </label>
                  <input wire:model= "ffinal" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="ffinal" type="date" placeholder="">
                  @error('ffinal') <span class="error">{{ $message }}</span> @enderror
                </div> 
              @endif 
                </div> 
             
    </x-slot>

    <x-slot name="buttons">
        <button wire:click="$emit('closeModal')" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">Cancelar</button>
        <button wire:click="agregarElemento({{ $_id}}, {{$id_rubro}})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
    </x-slot>

</x-modal>