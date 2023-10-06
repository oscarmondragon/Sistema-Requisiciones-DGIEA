<x-modal>
  <x-slot name="title">
    Descripcion del bien o servicio
  </x-slot>
  <x-slot name="content">
    <div>
      <div class="mb-4">
        <input type="hidden" wire:model="_id">
        <label class="block mb-2" for="descripcion">
          Descripción
        </label>
        <input autofocus wire:model="descripcion" required class="inputs-formulario" id="descripcion" type="text" placeholder="Descripción">
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
        <label class="block mb-2" for="precioUnitario">
          Precio Unitario
        </label>
        <input wire:model="precioUnitario" wire:change="calcularIvaImporte" required class="inputs-formulario" id="precioUnitario" type="number" min="1" placeholder="$ 0000.00">
        @error('precioUnitario') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      <div class="mb-4 text-right">
        <span> {{$iva}}</span>
        <input type="checkbox" id="checkIva" wire:model="checkIva" wire:change="calcularIvaImporte" name="checkIva">
        <label for="iva">IVA</label>

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
        <textarea wire:model="justificacionSoftware" required class="inputs-formulario" rows="2" cols="30" id="justificacionSoftware" placeholder="Descripción y justificación"></textarea>
        @error('justificacionSoftware') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>

      <div class="mb-4">
        <label class="block mb-2" for="importe">
          Selecciona los usuarios y escribe cuantos de ellos serán beneficiados
        </label>
        <label class="block mb-2" for="importe">
          Alumnos:
        </label>
        <input wire:model="numAlumnos"  x-data="{ numAlumnos: @entangle('numAlumnos') }" x-on:focus="numAlumnos = ''" x-on:input.debounce.500ms="numAlumnos = $event.target.value"  required class="inputs-formulario" id="numAlumnos" type="number" min="0" placeholder="Número de alumnos">
        @error('numAlumnos') <span class="text-rojo">{{ $message }}</span> @enderror

        <label class="block mb-2 mt-4" for="importe">
          Profesores / Investigadores:
        </label>
        <input wire:model="numProfesores"  x-data="{ numProfesores: @entangle('numProfesores') }" x-on:focus="numProfesores = ''" x-on:input.debounce.500ms="numProfesores = $event.target.value"  required class="inputs-formulario" id="numProfesores" type="number" min="0" placeholder="Número de profesores / investigadores">
        @error('numProfesores') <span class="text-rojo">{{ $message }}</span> @enderror

        <label class="block mb-2 mt-4" for="importe">
          Administrativos:
        </label>
        <input wire:model="numAdministrativos" x-data="{ numAdministrativos: @entangle('numAdministrativos') }" x-on:focus="numAdministrativos = ''" x-on:input.debounce.500ms="numAdministrativos = $event.target.value"  required class="inputs-formulario" id="numAdministrativos" type="number" min="0" placeholder="Número de administradores">
        @error('numAdministrativos') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      @endif
    </div>

  </x-slot>

  <x-slot name="buttons" class="mt-10">
    <button wire:click="agregarElemento({{ $_id}}, {{$id_rubro}})" class="btn-success sm:w-auto w-full">Guardar</button>
    <button wire:click="$emit('closeModal')" class="btn-warning sm:w-auto w-full">Cancelar</button>
  </x-slot>
</x-modal>