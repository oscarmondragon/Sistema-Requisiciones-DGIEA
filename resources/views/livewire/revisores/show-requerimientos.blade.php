<div x-data class="py-6">
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Estatus general de Requerimientos') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-textos_generales">
                <div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div>
                            {{--  <img src="img/ic_req_pendientes.png" alt="Image/png" class="inline-block"> --}}
                            <h3 class="inline-block text-xl pl-2">Requerimientos en revisión</h3>
                        </div>
                        @if (session('success'))
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                            <script>
                                Swal.fire({
                                    position: 'top-center',
                                    icon: 'success',
                                    text: '{{ session('success') }}',
                                    confirmButtonText: 'Aceptar',
                                    confirmButtonColor: '#62836C',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            </script>
                        @endif
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex flex-wrap items-end gap-2 -mt-6">
                                    <div class="sm:w-2/3  w-full">
                                        <select class="sm:w-auto  w-full" id="categoria" name="categoria"
                                            wire:model="categoria">
                                            <option value="0">Todo</option>
                                            @foreach ($tipoRequisicion as $tipo)
                                                <option value="{{ $tipo->id }}"
                                                    data-id-especial="{{ $tipo->id }}">{{ $tipo->descripcion }}
                                                </option>
                                            @endforeach
                                            <option value="3" data-id-especial="3">Pendientes de revisar</option>
                                            <option value="4" data-id-especial="4">En DGIEA</option>
                                            <option value="5" data-id-especial="5">En SIIA</option>

                                        </select>
                                        <input type="text" wire:model.live="search"
                                            class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-full"
                                            placeholder="Buscar por clave, tipo...">

                                    </div>
                                    <div class="flex-1 md:mt-0 mt-2">
                                        <p class="text-verde font-semibold">Filtrar por fecha</p>
                                        <input type="date" name="f_inicial" id="f_inicial"
                                            wire:model.live="f_inicial"
                                            class="bg-blanco text-textos_generales rounded-md border-transparent h-10 sm:w-auto w-full">
                                        <input type="date" name="f_final" id="f_final" wire:model.live="f_final"
                                            class="bg-blanco text-textos_generales rounded-md border-transparent h-10 md:mt-0 mt-2 sm:w-auto w-full">
                                    </div>
                                </div>

                                <div class="text-end mt-3">
                                    <button class="bg-blue-600" wire:click="limpiarFiltros">Limpiar filtros</button>
                                </div>


                                @if ($requerimientos->first())
                                    <div class="overflow-x-auto">
                                        <table class="table-auto text-left w-full mt-6">
                                            <thead>
                                                <tr class="bg-blanco text-xs">
                                                    <th class="w-[13%] cursor-pointer"
                                                        wire:click="sort('id_requerimiento')">
                                                        Clave requerimiento
                                                        <span class="text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[10%] cursor-pointer" wire:click="sort('clave_siia')">
                                                        Clave SIIA
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[12%] cursor-pointer"
                                                        wire:click="sort('id_requerimiento')">
                                                        Concepto
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[12%] cursor-pointer"
                                                        wire:click="sort('id_requerimiento')">
                                                        Clave proyecto
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[11%] cursor-pointer"
                                                        wire:click="sort('nombre_cuenta')">
                                                        Rubro
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[13%] cursor-pointer" wire:click="sort('descripcion')">
                                                        Tipo requerimiento
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[12%] sm:text-center cursor-pointer"
                                                        wire:click="sort('estado')">
                                                        Estatus
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[10%] cursor-pointer"
                                                        wire:click="sort('modificacion')">
                                                        Ultima modificación
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[7%]">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-sm">

                                                @foreach ($requerimientos as $requerimiento)
                                                    <tr class="border-b-gray-200 border-transparent">
                                                        <td> {{ $requerimiento->id_requerimiento }} </td>
                                                        <td> {{ $requerimiento->clave_siia }} </td>
                                                        <td> {{ strlen($requerimiento->concepto) > 80 ?  substr($requerimiento->concepto, 0, 80) . '...' : $requerimiento->concepto}} </td>
                                                        <td>
                                                            {{ $requerimiento->clave_digcyn == null ? $requerimiento->clave_uaem : $requerimiento->clave_digcyn }}
                                                        </td>
                                                        <td> {{ $requerimiento->nombre_cuenta }} </td>
                                                        <td> {{ $requerimiento->descripcion }} </td>
                                                        <td class="sm:text-center">
                                                            @if ($requerimiento->color_estado == 'VERDE')
                                                                <span
                                                                    class="bg-green-200 text-green-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-green-300">
                                                                    {{ $requerimiento->estado }}
                                                                </span>
                                                            @elseif($requerimiento->color_estado == 'ROJO')
                                                                <span
                                                                    class="bg-red-200 text-red-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-red-300">
                                                                    {{ $requerimiento->estado }}
                                                                </span>
                                                            @elseif($requerimiento->color_estado == 'AZUL')
                                                                <span
                                                                    class="bg-blue-200 text-blue-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-blue-300">
                                                                    {{ $requerimiento->estado }}
                                                                </span>
                                                            @elseif($requerimiento->color_estado == 'ROSA')
                                                                <span
                                                                    class="bg-pink-200 text-pink-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-pink-300">
                                                                    {{ $requerimiento->estado }}
                                                                </span>
                                                            @elseif($requerimiento->color_estado == 'NARANJA')
                                                                <span
                                                                    class="bg-orange-200 text-orange-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-orange-300">
                                                                    {{ $requerimiento->estado }}
                                                                </span>
                                                            @elseif($requerimiento->color_estado == 'AMARILLO')
                                                                <span
                                                                    class="bg-yellow-200 text-yellow-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-yellow-300">
                                                                    {{ $requerimiento->estado }}
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="bg-cyan-200 text-cyan-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-cyan-300">
                                                                    {{ $requerimiento->estado }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td> {{ $requerimiento->modificacion }}</td>

                                                        @if ($requerimiento->tipo_requerimiento == 1)
                                                            @if ($rol == 2 || $rol == 1)
                                                                <td>
                                                                    <a href="{{ route('adquisicion-admin.ver', $requerimiento->id) }}">
                                                                        <button class="btn-tablas" title="Ver">
                                                                            <img src="{{ 'img/botones/btn_ver.jpeg' }}"
                                                                                alt="Image/png">
                                                                        </button>
                                                                    </a>
                                                                </td>
                                                            @else
                                                                @if (in_array($requerimiento->tipo_estado, [2]))
                                                                    <td>
                                                                        <a href="{{ route('adquisicion.revisar', ['id' => $requerimiento->id]) }}">
                                                                            @if ($requerimiento->id_estatus == 4)
                                                                                <button class="btn-tablas"
                                                                                    title="Revisar">
                                                                                    <img src="{{'img/botones/btn_revisar.png'}}" alt="Botón Revisar">
                                                                                </button>
                                                                            @else
                                                                                <button class="btn-tablas"
                                                                                    title="Actualizar">
                                                                                    <img src="{{'img/botones/btn_actualizar.png'}}" alt="Botón Actualizar">
                                                                                </button>
                                                                            @endif
                                                                        </a>
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <a href="{{ route('adquisicion.revisar', [$requerimiento->id, $requerimiento->id_requisicion_detalle]) }}">
                                                                            <button class="btn-tablas"
                                                                                title="Actualizar">
                                                                                <img src="{{'img/botones/btn_actualizar.png'}}" alt="Botón Actualizar">
                                                                            </button>
                                                                        </a>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @elseif($requerimiento->tipo_requerimiento == 2)
                                                            @if ($rol == 2 || $rol == 1)
                                                                <td>
                                                                    <a href="{{ route('solicitud-admin.ver', $requerimiento->id) }}">
                                                                        <button class="btn-tablas" title="Ver">
                                                                            <img src="{{ 'img/botones/btn_ver.jpeg' }}" alt="Botón Ver">
                                                                        </button>
                                                                    </a>
                                                                </td>
                                                            @else
                                                                <td>
                                                                    <a href="{{ route('solicitud.revisar', $requerimiento->id) }}">
                                                                        @if ($requerimiento->id_estatus == 4)
                                                                            <button class="btn-tablas"
                                                                                title="Revisar">
                                                                                <img src="{{'img/botones/btn_revisar.png'}}" alt="Botón Revisar">
                                                                            </button>
                                                                        @else
                                                                            <button class="btn-tablas"
                                                                                title="Actualizar">
                                                                                <img src="{{'img/botones/btn_actualizar.png'}}" alt="Botón Actualizar">
                                                                            </button>
                                                                        @endif
                                                                    </a>
                                                                </td>
                                                            @endif
                                                        @endif
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <h2 class="text-center font-bold mt-5">
                                        No hay requerimientos en revisión.
                                    </h2>
                                @endif
                                <div class="mt-5">
                                    {{ $requerimientos->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
