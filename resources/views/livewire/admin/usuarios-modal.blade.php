<x-modal>
    <x-slot name="title">
        {{ $idUser == 0 ? 'Agregar nuevo usuario' : 'Editar usuario' }}
    </x-slot>
    <x-slot name="content">
        @if (session('error'))
            <div
                class="bg-red-100 text-red-500 font-bold py-1 px-2 rounded-sm border-l-4 border-red-500 text-center sm:mt-0 mt-4">
                {{ session('error') }}
            </div>
        @endif
        <div x-data x-init="usuario = '{{ $idUser }}'">
            <form wire:submit.prevent="@if ($idUser == 0) store @else update @endif">
                @csrf
                <div x-show="usuario == 0">
                    <div>
                        <label class="block mb-2" for="nombre">
                            Nombre<span class="text-rojo">*</span>:
                        </label>
                        <input type="text" name="nombre" id="nombre" wire:model="nombre" class="inputs-formulario"
                            placeholder="Nombre">
                        @error('nombre')
                            <span class=" text-rojo block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block mb-2" for="apellidoPaterno">
                            Apellido paterno<span class="text-rojo">*</span>:
                        </label>
                        <input type="text" name="apellidoPaterno" id="apellidoPaterno" wire:model="apellidoPaterno"
                            class="inputs-formulario" placeholder="Apellido paterno">
                        @error('apellidoPaterno')
                            <span class=" text-rojo block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block mb-2" for="apellidoMaterno">
                            Apellido materno:
                        </label>
                        <input type="text" name="apellidoMaterno" id="apellidoMaterno" wire:model="apellidoMaterno"
                            class="inputs-formulario" placeholder="Apellido Materno">
                        @error('apellidoMaterno')
                            <span class=" text-rojo block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block mb-2" for="email">
                            Correo electrónico<span class="text-rojo">*</span>:
                        </label>
                        <input type="text" name="email" id="email" wire:model="email" class="inputs-formulario"
                            placeholder="Correo electrónico">
                        @error('email')
                            <span class=" text-rojo block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block mb-2" for="password">
                            Constraseña nueva<span class="text-rojo">*</span>:
                        </label>
                        <input type="password" name="password" id="password" wire:model="password"
                            class="inputs-formulario" placeholder="Contraseña">
                        @error('password')
                            <span class=" text-rojo block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block mb-2" for="passwordConfirm">
                            Confirmar constraseña<span class="text-rojo">*</span>:
                        </label>
                        <input type="password" name="passwordConfirm" id="passwordConfirm" wire:model="passwordConfirm"
                            class="inputs-formulario" placeholder="Confirmar contraseña">
                        @error('passwordConfirm')
                            <span class=" text-rojo block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div x-show="usuario != 0">
                    <label class="block mb-2 sm:mt-0 mt-4" for="estatus">
                        Estatus<span class="text-rojo">*</span>:
                    </label>
                    <select name="estatus" id="estatus" wire:model="estatus" class="w-full">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="rolUsuario" class="block mb-2">
                        Rol del usuario<span class="text-rojo">*</span>:
                    </label>
                    <select name="rolUsuario" id="rolUsuario" wire:model="rolUsuario" class="w-full">
                        <option value="0">Selecciona una opción</option>
                        @foreach ($rolesUsuarios as $rolUsuario)
                            <option value="{{ $rolUsuario->id }}"> {{ $rolUsuario->descripcion }} </option>
                        @endforeach
                    </select>
                    @error('rolUsuario')
                        <span class=" text-rojo block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4 sm:-mb-10 text-end">
                    <button type="submit" class="btn-success sm:w-auto w-full">Guardar</button>
                    <button type="button" wire:click="$emit('closeModal')" class="btn-warning sm:w-auto w-full">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

        <div>
            <x-slot name="buttons">
            </x-slot>
        </div>
    </x-slot>
</x-modal>
