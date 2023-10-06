<x-modal>
  <x-slot name="title">
    Descripcion del recurso
  </x-slot>
  <x-slot name="content">
    <div class="w-full">
    <div>
        <label class="block mb-2" for="concepto">
          Concepto
        </label>
        <input wire:model="concepto" required class="inputs-formulario" id="concepto" type="text" placeholder="Concepto" autofocus>
        @error('concepto') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      <div class="mt-4">
        <input type="hidden" wire:model="_id">
        <label class="block mb-2" for="importe">
          Importe
        </label>
        <input wire:model="importe" required class="inputs-formulario" id="importe" type="number" placeholder="$ 0000.00">
        @error('importe') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      <div class="my-4">
        <label class="block mb-2" for="justificacionS">
          Justificación
        </label>
        <textarea wire:model="justificacionS" required class="inputs-formulario" rows="2" cols="30" id="justificacionS" placeholder="Justificación"></textarea>
        @error('justificacionS') <span class="text-rojo mb-4">{{ $message }}</span> @enderror
      </div>

      @if ($id_rubro_especial == '2')
      <label>Periodo</label>
      <div class="mt-2 mb-6 sm:grid sm:grid-cols-2 flex-col">
        <div class="sm:mr-2 mr-0 flex-col">
          <label class="block mb-1" for="finicial">
            Fecha inicial:
          </label>
          <input wire:model="finicial" required class="inputs-formulario" id="finicial" type="date" placeholder="">
          @error('finicial') <span class="text-rojo">{{ $message }}</span> @enderror
        </div>

        <div class="flex-col">
          <label class="block mb-1 sm:mt-0 mt-4" for="ffinal">
            Fecha final:
          </label>
          <input wire:model="ffinal" required class="inputs-formulario" id="ffinal" type="date" placeholder="">
          @error('ffinal') <span class="text-rojo">{{ $message }}</span> @enderror
        </div>
      </div>
      @endif
    </div>
  </x-slot>

  <x-slot name="buttons" class="mt-2">
    <button wire:click="agregarElemento({{ $_id}}, {{$id_rubro}})" class="btn-success sm:w-auto w-full">
      Guardar
    </button>
    <button wire:click="$emit('closeModal')" class="btn-warning sm:w-auto w-full">
      Cancelar
    </button>
  </x-slot>
</x-modal>