<div x-data class="sm:py-6 my-3">
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight ml-4">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-textos_generales">
                <div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        @if (session('success'))
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                            <script>
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    text: '{{ session('success') }}',
                                    confirmButtonText: 'Aceptar',
                                    confirmButtonColor: '#62836C',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            </script>
                        @endif
                        @if (session('error'))
                            <div
                                class="bg-red-100 text-red-500 font-bold py-1 px-2 rounded-sm border-l-4 border-red-500 text-center sm:mt-0 mt-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        <h1 class="text-dorado">Usuarios</h1>
                        {{-- Filtros --}}

                        <div class="sm:flex items-start mt-4">
                            <div class="">
                                <select class="flex-auto sm:w-auto w-full" id="categoria" name="categoria"
                                    wire:model="categoria">
                                    <option value="0">Todo</option>
                                    <option value="1">Administradores Externos</option>
                                    <option value="2">Administradores Internos</option>
                                    <option value="3">Revisores Externos</option>
                                    <option value="4">Revisores Internos</option>
                                    <option value="5">Inactivos</option>
                                    <option value="6">Eliminados</option>
                                </select>
                            </div>

                            <div class="flex-auto sm:w-64">
                                <input type="text" id="search" name="search" wire:model="search"
                                    class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-full"
                                    placeholder="Buscar por nombre, correo electrónico...">
                            </div>

                            <div class="flex-auto sm:w-auto sm:mt-0 mt-4">
                                <button type="button" class="bg-blue-600 hover:bg-blue-800"
                                    wire:click="limpiarFiltros">
                                    Limpiar filtros
                                </button>
                            </div>
                        </div>

                        <div class="text-end sm:mt-0">
                            <button class="btn-success" title="Agregar nuevo usuario"
                                onclick="Livewire.emit('openModal', 'admin.usuarios-modal')">
                                Nuevo usuario
                            </button>
                        </div>

                        @if ($users->first())
                            <div class="overflow-x-auto mt-10">
                                <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                                    <thead>
                                        <tr class="bg-blanco">
                                            <th class="cursor-pointer w-[30%]" wire:click="sort('name')">
                                                Nombre<span class="pl-1 text-verde font-bold">&#8645;</span>
                                            </th>
                                            <th class="cursor-pointer w-[30%]" wire:click="sort('email')">
                                                Correo electrónico<span class="pl-1 text-verde font-bold">&#8645;</span>
                                            </th>
                                            <th class="cursor-pointer w-[20%]" wire:click="sort('rol')">
                                                Rol del usuario<span class="pl-1 text-verde font-bold">&#8645;</span>
                                            </th>
                                            <th class="cursor-pointer w-[10%]" wire:click="sort('estatus')">
                                                Estatus<span class="pl-1 text-verde font-bold">&#8645;</span>
                                            </th>
                                            <th class="w-[10%]">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $usuario)
                                            <tr class="border border-b-gray-50 border-transparent">
                                                <th>
                                                    {{ $usuario->name . ' ' . $usuario->apePaterno . ' ' . $usuario->apeMaterno }}
                                                </th>
                                                <th> {{ $usuario->email }} </th>
                                                <th> {{ $usuario->tipoRol->descripcion }} </th>
                                                <th>
                                                    @if ($usuario->trashed())
                                                        <span
                                                            class="bg-red-200 text-red-700 rounded-full p-1 px-2 text-xs font-bold 
                                                            text-center block mx-1 border border-red-300">
                                                            Eliminado
                                                        </span>
                                                    @elseif($usuario->estatus == 0)
                                                        <span
                                                            class="bg-yellow-200 text-yellow-700 rounded-full p-1 px-2 text-xs font-bold 
                                                    text-center block mx-1 border border-yellow-300">
                                                            Inactivo
                                                        </span>
                                                    @elseif($usuario->estatus == 1)
                                                        <span
                                                            class="bg-green-200 text-green-700 rounded-full p-1 px-2 text-xs font-bold 
                                                text-center block mx-1 border border-green-300">
                                                            Activo
                                                        </span>
                                                    @endif
                                                </th>
                                                <th class="flex sm:flex-row sm:gap-x-2 flex-col text-center">
                                                    @if ($usuario->trashed())
                                                        <div class="mx-auto text-center">
                                                            <button class="btn-tablas" title="Restaurar usuario"
                                                                @click="restaurarUsuario({{ $usuario->id }}, '{{ $usuario->name }}')">
                                                                <img src="{{ 'img/botones/btn_restaurar.png' }}"
                                                                    alt="Botón restaurar">
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div>
                                                            <button type="button" class="btn-tablas" title="Editar"
                                                                onclick="Livewire.emit('openModal', 'admin.usuarios-modal', 
                                                    {idUser:{{ isset($usuario->id) ? $usuario->id : 0 }}, nombre:'{{ $usuario->name }}',
                                                    apellidoPaterno:'{{ $usuario->apePaterno }}', apellidoMaterno:'{{ $usuario->apeMaterno }}', email:'{{ $usuario->email }}', 
                                                    password:'{{ $usuario->password }}', passwordConfirm: null, estatus:{{ $usuario->estatus }}, rolUsuario:{{ $usuario->rol }} })">
                                                                <img src="{{ 'img/botones/btn_editar.png' }}"
                                                                    alt="Botón editar.">
                                                            </button>
                                                        </div>
                                                        <div>
                                                            <button type="button" class="btn-tablas" title="Eliminar"
                                                                @click="deleteUsuario({{ $usuario->id }}, '{{ $usuario->name }}')">
                                                                <img src="{{ 'img/botones/btn_eliminar.png' }}"
                                                                    alt="Botón eliminar.">
                                                            </button>
                                                        </div>
                                                    @endif
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-7">
                                {{ $users->links() }}
                            </div>
                        @else
                            <h2 class="text-center font-bold mt-10">
                                No hay usuarios para mostrar.
                            </h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function deleteUsuario(idUsuario, nombre) {
                Swal.fire({
                    title: '¿Estás seguro de eliminar el usuario ' + nombre + '?',
                    position: 'center',
                    icon: 'warning',
                    iconColor: '#9D9361',
                    showCancelButton: true,
                    confirmButtonColor: '#E86562',
                    cancelButtonColor: '#62836C',
                    confirmButtonText: 'Si, eliminar',
                    cancelButtonText: 'Cancelar',

                }).then((result) => {
                    if (result.isConfirmed) {
                        // Manda llamar el metodo liveware
                        window.livewire.emit('delete', idUsuario);
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#62836C',
                            text: 'Se eliminó correctamente el usuario',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }

            function restaurarUsuario(id, nombre) {
                Swal.fire({
                    title: '¿Quieres restaurar el usuario ' + nombre + '?',
                    text: 'El usuario se restaurara conservando el último estatus antes de ser eliminado, el cual puede ser activo o inactivo.',
                    position: 'center',
                    icon: 'warning',
                    iconColor: '#9D9361',
                    showCancelButton: true,
                    confirmButtonColor: '#62836C',
                    cancelButtonColor: '#E86562',
                    confirmButtonText: 'Si, restaurar',
                    cancelButtonText: 'Cancelar',

                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('restaurarUsuario', id);
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#62836C',
                            text: 'El usuario se restauro correctamente.',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }
        </script>
    @endpush
</div>
