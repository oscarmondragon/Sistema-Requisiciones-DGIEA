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
                <textarea wire:model="descripcion" class="inputs-formulario" id="descripcion" rows="2" cols="30"
                    placeholder="Descripción" autofocus required></textarea>
                @error('descripcion')
                    <span class="text-rojo">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-2">
                    <div>
                        <label class="inline-block mb-2" for="cantidad">
                            Cantidad
                        </label>
                    </div>
                    <div class="inline-block text-end" title="Nota: Si la captura de su requerimiento va hacer de forma general, se sugiere capturar un 1 en cantidad y en precio unitario colocar el subtotal de su cotización.">
                        <svg class="w-6 h-6 text-verde inline-block" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M2 12a10 10 0 1 1 20 0 10 10 0 0 1-20 0Zm9-3a1.5 1.5 0 0 1 2.5 1.1 1.4 1.4 0 0 1-1.5 1.5 1 1 0 0 0-1 1V14a1 1 0 1 0 2 0v-.5a3.4 3.4 0 0 0 2.5-3.3 3.5 3.5 0 0 0-7-.3 1 1 0 0 0 2 .1c0-.4.2-.7.5-1Zm1 7a1 1 0 1 0 0 2 1 1 0 1 0 0-2Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <input wire:model="cantidad" wire:change="calcularIvaImporte" required class="inputs-formulario"
                    id="cantidad" type="number" min="1" placeholder="Cantidad">
                @error('cantidad')
                    <span class="text-rojo">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block mb-2" for="precio_unitario">
                    Precio Unitario
                </label>
                <input wire:model="precio_unitario" wire:change="calcularIvaImporte" required class="inputs-formulario"
                    id="precio_unitario" type="number" min="1" placeholder="$ 0000.00">
                @error('precio_unitario')
                    <span class="text-rojo">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4 text-right">
                <span> {{ $porcentajeIva }}%</span>
                <input type="checkbox" checked id="checkIva" wire:model="checkIva" wire:change="calcularIvaImporte"
                    name="checkIva">
                <label for="checkIva">IVA</label>

            </div>
            <div class="mb-4">
                <label class="block mb-2" for="importe">
                    Importe
                </label>
                <input wire:model="importe" required readonly class="inputs-formulario cursor-not-allowed"
                    id="importe" type="text" placeholder="$ 0000.00">
                @error('importe')
                    <span class="text-rojo">{{ $message }}</span>
                @enderror
            </div>
            @if ($id_rubro_especial == '1')
                <div class="mb-4">
                    <label class="block mb-2" for="importe">
                        Descripción y justificación al cual será dedicado el software:
                    </label>
                    <textarea wire:model="justificacion_software" required class="inputs-formulario" rows="2" cols="30"
                        id="justificacion_software" placeholder="Descripción y justificación"></textarea>
                    @error('justificacion_software')
                        <span class="text-rojo">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-bold" for="importe">
                        Selecciona los usuarios y escribe cuantos de ellos serán beneficiados
                    </label>
                    <label class="block mb-2" for="importe">
                        Alumnos:
                    </label>
                    <input wire:model="alumnos" x-data="{ alumnos: @entangle('alumnos') }" x-on:focus="alumnos = ''"
                        x-on:input.debounce.500ms="alumnos = $event.target.value" required class="inputs-formulario"
                        id="alumnos" type="number" min="0" placeholder="Número de alumnos">
                    @error('alumnos')
                        <span class="text-rojo">{{ $message }}</span>
                    @enderror

                    <label class="block mb-2 mt-4" for="importe">
                        Profesores / Investigadores:
                    </label>
                    <input wire:model="profesores_invest" x-data="{ profesores_invest: @entangle('profesores_invest') }" x-on:focus="profesores_invest = ''"
                        x-on:input.debounce.500ms="profesores_invest = $event.target.value" required
                        class="inputs-formulario" id="profesores_invest" type="number" min="0"
                        placeholder="Número de profesores / investigadores">
                    @error('profesores_invest')
                        <span class="text-rojo">{{ $message }}</span>
                    @enderror

                    <label class="block mb-2 mt-4" for="importe">
                        Administrativos:
                    </label>
                    <input wire:model="administrativos" x-data="{ administrativos: @entangle('administrativos') }" x-on:focus="administrativos = ''"
                        x-on:input.debounce.500ms="administrativos = $event.target.value" required
                        class="inputs-formulario" id="administrativos" type="number" min="0"
                        placeholder="Número de administradores">
                    @error('administrativos')
                        <span class="text-rojo">{{ $message }}</span>
                    @enderror
                </div>
            @endif
        </div>

    </x-slot>

    <x-slot name="buttons" class="mt-10">
        <button wire:click="agregarElemento({{ $_id }}, {{ $id_rubro }})"
            class="btn-success sm:w-auto w-full">Guardar</button>
        <button wire:click="$emit('closeModal')" class="btn-warning sm:w-auto w-full">Cancelar</button>
    </x-slot>
</x-modal>
