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
        <input autofocus wire:model="descripcion" required class="inputs_formulario_adquisicion_descripcion" id="descripcion" type="text" placeholder="Descripción">
        @error('descripcion')
        <span class="text-rojo">{{ $message }}</span>@enderror
      </div>
      <div class="mb-4">
        <label class="block mb-2" for="cantidad">
          Cantidad
        </label>
        <input wire:model="cantidad" wire:change="calcularIvaImporte" required class="inputs_formulario_adquisicion_descripcion" id="cantidad" type="number" min="1"  placeholder="Cantidad">
        @error('cantidad') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      <div class="mb-4">
        <label class="block mb-2" for="precioUnitario">
          Precio Unitario
        </label>
        <input wire:model="precioUnitario" wire:change="calcularIvaImporte" required class="inputs_formulario_adquisicion_descripcion" id="precioUnitario" type="number" min="1" placeholder="$ Precio unitario">
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
        <input wire:model="importe" required readonly class="inputs_formulario_adquisicion_descripcion cursor-not-allowed" id="importe" type="text" placeholder="Importe">
        @error('importe') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      @if ($id_rubro_especial == '1')
      <div class="mb-4">
        <label class="block mb-2" for="importe">
          Descripción y justificación al cual será dedicado el software:
        </label>
        <textarea wire:model="justificacionSoftware" required class="inputs_formulario_adquisicion_descripcion" rows="2" cols="30" id="justificacionSoftware" placeholder="Descripción y justificación"></textarea>
        @error('justificacionSoftware') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>

      <div class="mb-4">
        <label class="block mb-2" for="importe">
          Selecciona los usuarios y escribe cuantos de ellos serán beneficiados
        </label>
        <label class="block mb-2" for="importe">
          Alumnos:
        </label>
        <input wire:model="numAlumnos" required class="inputs_formulario_adquisicion_descripcion" id="numAlumnos" type="number" min="0" placeholder="Número de alumnos">
        @error('numAlumnos') <span class="text-rojo">{{ $message }}</span> @enderror

        <label class="block mb-2 mt-4" for="importe">
          Profesores / Investigadores:
        </label>
        <input wire:model="numProfesores" required class="inputs_formulario_adquisicion_descripcion" id="numProfesores" type="number" min="0" placeholder="Número de profesores / investigadores">
        @error('numProfesores') <span class="text-rojo">{{ $message }}</span> @enderror

        <label class="block mb-2 mt-4" for="importe">
          Administrativos:
        </label>
        <input wire:model="numAdministrativos" required class="inputs_formulario_adquisicion_descripcion" id="numAdministrativos" type="number" min="0" placeholder="Número de administradores">
        @error('numAdministrativos') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      @endif
    </div>

  </x-slot>

  <x-slot name="buttons" class="mt-10">
    <button wire:click="agregarElemento({{ $_id}}, {{$id_rubro}})" class="btn-success">Guardar</button>
    <button wire:click="$emit('closeModal')" class="btn-warning">Cancelar</button>
  </x-slot>
</x-modal>