<x-modal>
    <x-slot name="title">
      Fechas para proyecto con clave: {{$clave_digcyn == null ? $clave_uaem : $clave_digcyn}}
    </x-slot>
    <x-slot name="content">
      <div class="w-full">
        <h2>Vigencia del proyecto</h2>
                <div class="flex-col mt-3">
                    <label class="block mb-1" for="fecha_inicio">Fecha de inicio<samp class="text-rojo">*</samp>:</label>
                    <input wire:model="fecha_inicio" class="inputs-formulario" id="fecha_inicio"
                        type="date">
                    @error('fecha_inicio')
                        <span class="text-rojo block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex-col mt-6">
                    <label class="block mb-1 sm:mt-0 mt-4" for="fecha_final">Fecha de
                        finalización<samp class="text-rojo">*</samp>:</label>
                    <input wire:model="fecha_final" class="inputs-formulario" id="fecha_final"
                        type="date">
                    @error('fecha_final')
                        <span class="text-rojo block">{{ $message }}</span>
                    @enderror
                </div>
        </div>
        <div class="w-full">
        <h2>Fechas limite para hacer requerimientos</h2>
          <div class="flex-col mt-3">
              <label class="block mb-1" for="fecha_limite_adquisiciones">Fecha limite para adquisiciones<samp class="text-rojo">*</samp>:</label>
              <input wire:model="fecha_limite_adquisiciones" class="inputs-formulario" id="fecha_limite_adquisiciones"
                  type="date">
              @error('fecha_limite_adquisiciones')
                  <span class="text-rojo block">{{ $message }}</span>
              @enderror
          </div>
          <div class="flex-col mt-6">
              <label class="block mb-1 sm:mt-0 mt-4" for="fecha_limite_solicitudes">Fecha limite para solicitudes<samp class="text-rojo">*</samp>:</label>
              <input wire:model="fecha_limite_solicitudes" class="inputs-formulario" id="fecha_limite_solicitudes"
                  type="date">
              @error('fecha_limite_solicitudes')
                  <span class="text-rojo block">{{ $message }}</span>
              @enderror
          </div>
  </div>
    </x-slot>
  
    <x-slot name="buttons" class="mt-2">
      <button wire:click="save" class="btn-success sm:w-auto w-full">
        Guardar
      </button>
      <button wire:click="$emit('closeModal')" class="btn-warning sm:w-auto w-full">
        Cancelar
      </button>
    </x-slot>
  </x-modal>