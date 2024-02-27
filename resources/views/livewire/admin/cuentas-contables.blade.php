<div x-data class="sm:py-6 my-3">
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight ml-4">
            {{ __('Cuentas contables') }}
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
                            <h1 class="text-dorado">Cuentas contables</h1>
                        {{-- Filtros --}}
                        <div class="mt-2">
                            <select class="sm:w-auto w-full" id="categoriaRequisicion" name="categoriaRequisicion"
                                wire:model="categoriaRequisicion">
                                <option value="0">Todo</option>
                                @foreach ($tiposRequisiciones as $requisicion)
                                    <option value="{{ $requisicion->id }}"> {{ $requisicion->descripcion }} </option>
                                @endforeach
                            </select>

                            <select class="sm:w-auto w-full sm:mt-0 mt-3" id="categoria" name="categoria" wire:model="categoria">
                                <option value="1">Activas</option>
                                <option value="0">Inactivas</option>
                                <option value="2">Eliminadas</option>
                            </select>
                        </div>
                   
                        <div class="sm:flex items-start mt-4">
                            <div class="flex-auto sm:w-64">
                                <input type="text" wire:model="search"
                                    class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-full"
                                    placeholder="Buscar por clave cuenta, nombre cuenta...">
                            </div>

                            <div class="flex-auto sm:w-32 w-auto sm:mt-0 mt-4">
                                <button type="button" class="bg-blue-600 hover:bg-blue-800"
                                    wire:click="limpiarFiltros">
                                    Limpiar filtros
                                </button>
                            </div>

                            <div class="sm:text-start text-end sm:-mt-2">
                                <button class="btn-success" title="Agregar cuenta contable"
                                    onclick="Livewire.emit('openModal', 'admin.cuentas-contables-modal')"
                                    name="agregarCuenta">
                                    Agregar cuenta
                                </button>
                            </div>
                        </div>

                        @if ($cuentas->first())
                            <div class="overflow-x-auto mt-5">
                                <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                                    <thead>
                                        <tr class="bg-blanco">
                                            <th class="cursor-pointer w-[10%]" wire:click="sort('id')">Clave cuenta
                                                <span class="pl-1 text-verde font-bold">&#8645;</span>
                                            </th>
                                            <th class="cursor-pointer w-[50%]" wire:click="sort('nombre_cuenta')">Nombre
                                                <span class="pl-1 text-verde font-bold">&#8645;</span>
                                            </th>
                                            <th class="cursor-pointer w-[20%]" wire:click="sort('tipo_requisicion')">
                                                Tipo requisición
                                                <span class="pl-1 text-verde font-bold">&#8645;</span>
                                            </th>
                                            <th class="cursor-pointer w-[10%] text-center" wire:click="sort('estatus')">Estatus
                                                <span class="pl-1 text-verde font-bold">&#8645;</span>
                                            </th>
                                            <th class="w-[10%] text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cuentas as $cuenta)
                                            <tr class="border border-b-gray-50 border-transparent">
                                                <td> {{ $cuenta->id }} </td>
                                                <td> {{ $cuenta->nombre_cuenta }} </td>
                                                <td> {{ $cuenta->tipoRequisicion->descripcion }} </td>
                                                <td>
                                                    @if(($cuenta->estatus == 0 || $cuenta->estatus) == 1 && $cuenta->trashed())
                                                        <span
                                                            class="bg-red-200 text-red-700 rounded-full p-1 px-2 text-xs font-bold 
                                                            text-center block mx-1 border border-red-300">
                                                            Eliminada
                                                        </span>
                                                    @elseif ($cuenta->estatus == 0)
                                                        <span
                                                            class="bg-yellow-200 text-yellow-700 rounded-full p-1 px-2 text-xs font-bold 
                                                            text-center block mx-1 border border-yellow-300">
                                                            Inactiva
                                                        </span>
                                                    @elseif($cuenta->estatus == 1)
                                                        <span
                                                            class="bg-green-200 text-green-700 rounded-full p-1 px-2 text-xs font-bold 
                                                text-center block mx-1 border border-green-300">
                                                            Activa
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="sm:grid grid-cols-2">
                                                    @if ($cuenta->trashed())
                                                        <div class="col-span-2 text-center">
                                                            <button type="button" class="btn-tablas">
                                                                <img src="{{ 'img/botones/btn_restaurar.png' }}"
                                                                    alt="Botón restaurar" title="Restaurar"
                                                                    class="btn-tablas"
                                                                    @click="restaurarCuenta('{{ $cuenta->id }}')">
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div>
                                                            <button type="button" class="btn-tablas"
                                                                onclick="Livewire.emit('openModal', 'admin.cuentas-contables-modal', {idClave:{{ $cuenta->id }}, idCuenta:{{ isset($cuenta->id_cuenta) ? $cuenta->id_cuenta : 0 }}, nombreCuenta: '{{ $cuenta->nombre_cuenta }}',
                                            tipoRequisicion: {{ $cuenta->tipo_requisicion }}, idEspecial: {{ isset($cuenta->id_especial) ? $cuenta->id_especial : 0 }}, estatus: {{ $cuenta->estatus }}, idUsuario: {{ $cuenta->id_usuario_sesion }} })">
                                                                <img src="{{ 'img/botones/btn_editar.png' }}"
                                                                    alt="Botón editar" title="Editar"
                                                                    class="btn-tablas">
                                                            </button>
                                                        </div>
                                                        <div>
                                                            <button type="button" class="btn-tablas">
                                                                <img src="{{ 'img/botones/btn_eliminar.png' }}"
                                                                    alt="Botón eliminar" title="Eliminar"
                                                                    class="btn-tablas"
                                                                    @click="deleteCuenta('{{ $cuenta->id }}')">
                                                            </button>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-5">
                                {{ $cuentas->links() }}
                            </div>
                        @else
                            <h2 class="text-center font-bold mt-8">
                                No hay cuentas contables para mostrar.
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
            function deleteCuenta(idDelete) {
                Swal.fire({
                    title: '¿Estás seguro de eliminar la cuenta contable ' + idDelete + '?',
                    text: 'La cuenta contable podra ser restaurada en el apartado de eliminadas.',
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
                        window.livewire.emit('delete', idDelete);
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#62836C',
                            text: 'Se eliminó correctamente la cuenta contable',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }

            function restaurarCuenta(idCuenta) {
                Swal.fire({
                    title: '¿Quieres restaurar la cuenta contable ' + idCuenta + '?',
                    text: 'La cuenta se restaurara conservando el último estatus antes de ser eliminada, el cual puede ser activa o inactiva.',
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
                        window.livewire.emit('restaurarCuenta', idCuenta);
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#62836C',
                            text: 'Se restauro correctamente la cuenta contable.',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }
        </script>
    @endpush
</div>
