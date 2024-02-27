<x-modal>
    <x-slot name="title">
        Cuenta contable
    </x-slot>
    <x-slot name="content">

        @if (session('error'))
            <div class="bg-red-100 text-red-500 font-bold py-1 px-2 rounded-sm border-l-4 border-red-500 text-center sm:mt-0 mt-4">
                {{ session('error') }}
            </div>
        @endif

        <div>
            {{-- wire:submit.prevent="store" --}}
            <form wire:submit.prevent="@if ($idCuenta == 0) store @else update @endif">
                @csrf
                <div class="mb-4 sm:mt-0 mt-4">
                    <label class="block mb-2" for="idClave">
                        Clave cuenta<span class="text-rojo">*</span>:
                    </label>
                    <input type="number" name="idClave" id="idClave" wire:model="idClave" class="inputs-formulario" placeholder="Clave de la cuenta">
                    @error('idClave')
                        <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2" for="nombreCuenta">
                        Nombre cuenta<span class="text-rojo">*</span>:
                    </label>
                    <input type="text" name="nombreCuenta" id="nombreCuenta" wire:model="nombreCuenta"
                        class="inputs-formulario uppercase" placeholder="Nombre de la cuenta contable">
                    @error('nombreCuenta')
                        <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2" for="tipoRequisicion">
                        Tipo de requerimiento al que aplica<span class="text-rojo">*</span>:
                    </label>
                    <select name="tipoRequisicion" id="tipoRequisicion" wire:model="tipoRequisicion"
                        wire:change="actualizarTipoRequisicion()" class="w-full" title="">
                        <option value="0">Selecciona una opción</option>
                        @foreach ($tiposRequisicion as $tipo)
                            <option value="{{ $tipo->id }}"> {{ $tipo->descripcion }} </option>
                        @endforeach
                    </select>
                    @error('tipoRequisicion')
                        <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2" for="idEspecial">
                        Particularidad:
                    </label>
                    <select name="idEspecial" id="idEspecial" wire:model="idEspecial" class="w-full" 
                    title="Agrega campos adicionales según la particularidad seleccionada.">
                        <option value="0">Ninguna</option>
                        @foreach ($cuentasEspeciales as $cuenta)
                            <option value="{{ $cuenta->id }}">
                                {{ $cuenta->descripcion }} </option>
                        @endforeach
                    </select>
                    @error('idEspecial')
                        <span class=" text-rojo sm:inline-block block">{{ $message }}</span>
                    @enderror
                </div>

                <div x-data x-init="estatusCuenta = '{{ $idCuenta }}'">
                    <div x-show="estatusCuenta != 0">
                        <label class="block mb-2" for="estatus">Estatus la cuenta:</label>
                        <select name="estatus" id="estatus" wire:model="estatus" class="w-full">
                            <option value="1">Activa</option>
                            <option value="0">Inactiva</option>
                        </select>
                    </div>
                </div>

                <div class="text-end sm:mt-10 mt-5 sm:-mb-7">
                    <button type="submit" class="btn-success sm:w-auto w-full">Guardar</button>
                    <button type="button" wire:click="$emit('closeModal')"
                        class="btn-warning sm:w-auto w-full">Cancelar</button>
                </div>

                <x-slot name="buttons">
                </x-slot>
                
            </form>
        </div>
    </x-slot>
</x-modal>
