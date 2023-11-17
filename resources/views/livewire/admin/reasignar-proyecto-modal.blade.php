<x-modal>
    <x-slot name="title">
      Reasignar proyecto con clave: {{$id_proyecto}}
    </x-slot>
    <x-slot name="content">
      <div class="w-full">
          <label for="idRevisorAsignados">
              Revisor:
            </label>
            <select class="sm:w-auto w-full"  id="nuevoRevisor" name="nuevoRevisor" wire:model="nuevoRevisor" >
              <option value="0">Seleccione una opción</option>
              @foreach ($revisores as $revisor)
              @if ($revisor['id']!= $id_revisor)
              <option value="{{ $revisor['id'] }}" >{{ $revisor['name']}} {{ $revisor['apePaterno']}} {{ $revisor['apeMaterno']}}</option>  
              @endif
              @endforeach
            </select>
            @error('nuevoRevisor') <span class=" text-rojo">{{ $message }}</span> @enderror
      </div>
    </x-slot>
  
    <x-slot name="buttons" class="mt-2">
      <button wire:click="saveReasignar" class="btn-success sm:w-auto w-full">
        Guardar
      </button>
      <button wire:click="$emit('closeModal')" class="btn-warning sm:w-auto w-full">
        Cancelar
      </button>
    </x-slot>
  </x-modal>