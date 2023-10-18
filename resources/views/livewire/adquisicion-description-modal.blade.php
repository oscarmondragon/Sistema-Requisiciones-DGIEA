<x-modal>
  <x-slot name="title">
    Descripción del bien o servicio
  </x-slot>
  <x-slot name="content">
    <div>
      <div class="mb-4">
        <input type="hidden" wire:model="_id">
        <label class="block mb-2" for="descripcion">
          Descripción
        </label>
        <!-- <input autofocus wire:model="descripcion" required class="inputs-formulario" id="descripcion" type="text" placeholder="Descripción"> -->
        <textarea wire:model="descripcion" class="inputs-formulario" id="descripcion" rows="2" cols="30" placeholder="Descripción" autofocus required></textarea>
        @error('descripcion')
        <span class="text-rojo">{{ $message }}</span>@enderror
      </div>
      <div class="mb-4">
        <label class="block mb-2" for="cantidad">
          Cantidad
        </label>
        <input wire:model="cantidad" wire:change="calcularIvaImporte" required class="inputs-formulario" id="cantidad" type="number" min="1"  placeholder="Cantidad">
        @error('cantidad') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      <div class="mb-4">
        <label class="block mb-2" for="precio_unitario">
          Precio Unitario
        </label>
        <input wire:model="precio_unitario" wire:change="calcularIvaImporte" required class="inputs-formulario" id="precio_unitario" type="number" min="1" placeholder="$ 0000.00">
        @error('precio_unitario') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      <div class="mb-4 text-right">
        <span> {{ $porcentajeIva }}%</span>
        <input type="checkbox" checked id="checkIva" wire:model="checkIva" wire:change="calcularIvaImporte" name="checkIva">
        <label for="checkIva">IVA</label>

      </div>
      <div class="mb-4">
        <label class="block mb-2" for="importe">
          Importe
        </label>
        <input wire:model="importe" required readonly class="inputs-formulario cursor-not-allowed" id="importe" type="text" placeholder="$ 0000.00">
        @error('importe') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      @if ($id_rubro_especial == '1')
      <div class="mb-4">
        <label class="block mb-2" for="importe">
          Descripción y justificación al cual será dedicado el software:
        </label>
        <textarea wire:model="justificacion_software" required class="inputs-formulario" rows="2" cols="30" id="justificacion_software" placeholder="Descripción y justificación"></textarea>
        @error('justificacion_software') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>

      <div class="mb-4">
        <label class="block mb-2" for="importe">
          Selecciona los usuarios y escribe cuantos de ellos serán beneficiados
        </label>
        <label class="block mb-2" for="importe">
          Alumnos:
        </label>
        <input wire:model="alumnos"  x-data="{ alumnos: @entangle('alumnos') }" x-on:focus="alumnos = ''" x-on:input.debounce.500ms="alumnos = $event.target.value"  required class="inputs-formulario" id="alumnos" type="number" min="0" placeholder="Número de alumnos">
        @error('alumnos') <span class="text-rojo">{{ $message }}</span> @enderror

        <label class="block mb-2 mt-4" for="importe">
          Profesores / Investigadores:
        </label>
        <input wire:model="profesores_invest"  x-data="{ profesores_invest: @entangle('profesores_invest') }" x-on:focus="profesores_invest = ''" x-on:input.debounce.500ms="profesores_invest = $event.target.value"  required class="inputs-formulario" id="profesores_invest" type="number" min="0" placeholder="Número de profesores / investigadores">
        @error('profesores_invest') <span class="text-rojo">{{ $message }}</span> @enderror

        <label class="block mb-2 mt-4" for="importe">
          Administrativos:
        </label>
        <input wire:model="administrativos" x-data="{ administrativos: @entangle('administrativos') }" x-on:focus="administrativos = ''" x-on:input.debounce.500ms="administrativos = $event.target.value"  required class="inputs-formulario" id="administrativos" type="number" min="0" placeholder="Número de administradores">
        @error('administrativos') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      @endif
    </div>

  </x-slot>

  <x-slot name="buttons" class="mt-10">
    <button wire:click="agregarElemento({{ $_id}}, {{$id_rubro}})" class="btn-success sm:w-auto w-full">Guardar</button>
    <button wire:click="$emit('closeModal')" class="btn-warning sm:w-auto w-full">Cancelar</button>
  </x-slot>
</x-modal>