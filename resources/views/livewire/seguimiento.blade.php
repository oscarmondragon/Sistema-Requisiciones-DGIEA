<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <h3>Requerimientos en proceso </h3>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 ">
                                <div class="flex flex-wrap items-end gap-2 -mt-7">
                                    <div class="w-2/3">
                                        <select class="w-auto" id="categoria" name="categoria" wire:model="categoria"
                                            @change="$wire.filterByCategory($event.target.selectedOptions[0].getAttribute('data-id-especial'))">
                                            <option value="0">Todo</option>
                                            @foreach ($tipoRequisicion as $tipo)
                                                <option value="{{ $tipo->id }}"
                                                    data-id-especial="{{ $tipo->id }}">{{ $tipo->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="text" wire:model.live="search"
                                            class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-auto"
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
                                        <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto mt-6">
                                            <thead>
                                                <tr class="bg-blanco">
                                                    <th class="w-[12%] cursor-pointer" wire:click="sort('id_requerimiento')">
                                                        Clave <span class="pl-1 text-verde font-bold">&#8645;</span> requerimiento
                                                    </th>
                                                    <th class="w-[12%] cursor-pointer" wire:click="sort('claveSIIA')">
                                                        Clave SIIA
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[15%] cursor-pointer" wire:click="sort('nombre_cuenta')">
                                                        Rubro
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[18%] cursor-pointer" wire:click="sort('req')">
                                                        Descripción
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[1%] cursor-pointer" wire:click="sort('tipo_requerimiento')">
                                                        Tipo <span class="pl-1 text-verde font-bold">&#8645;</span> requerimiento
                                                    </th>
                                                    <th class="w-[12%] cursor-pointer" wire:click="sort('estado')">
                                                        Estatus
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[10%] cursor-pointer" wire:click="sort('modificacion')">
                                                        Ultima <span class="pl-1 text-verde font-bold">&#8645;</span> modificación
                                                    </th>
                                                    <th class="w-[20%] cursor-pointer" wire:click="sort('observaciones')">
                                                        Observaciones
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[8%]">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    @foreach ($requerimientos as $adquisicion => $valor)
                                                        <td> {{ $valor->id_requerimiento }}</td>
                                                        <td> {{ $valor->claveSIIA }}</td>
                                                        <td> {{ $valor->nombre_cuenta }}</td>
                                                        <td> {{ $valor->concepto }}</td>
                                                        <td> {{ $valor->req }}</td>
                                                        <td class="sm:text-center">
                                                            @if ($valor->color_estado == 'VERDE')
                                                                <span
                                                                    class="bg-green-200 text-green-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-green-300">
                                                                    {{ $valor->estado }}
                                                                </span>
                                                            @elseif($valor->color_estado == 'ROJO')
                                                                <span
                                                                    class="bg-red-200 text-red-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-red-300">
                                                                    {{ $valor->estado }}
                                                                </span>
                                                            @elseif($valor->color_estado == 'AZUL')
                                                                <span
                                                                    class="bg-blue-200 text-blue-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-blue-300">
                                                                    {{ $valor->estado }}
                                                                </span>
                                                            @elseif($valor->color_estado == 'ROSA')
                                                                <span
                                                                    class="bg-pink-200 text-pink-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-pink-300">
                                                                    {{ $valor->estado }}
                                                                </span>
                                                            @elseif($valor->color_estado == 'NARANJA')
                                                                <span
                                                                    class="bg-orange-200 text-orange-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-orange-300">
                                                                    {{ $valor->estado }}
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="bg-yellow-200 text-yellow-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-yellow-300">
                                                                    {{ $valor->estado }}
                                                                </span>
                                                            @endif
                                                        </td>


                                                        <td> {{ $valor->modificacion }}</td>
                                                        <td> {{ $valor->observaciones }}</td>
                                                        <th>
                                                            @if ($valor->tipo_requerimiento == 1)
                                                                @if ($valor->id_estatus == 5 and Session::get('id_user') == $valor->emisor)
                                                                    <a href="{{ route('adquisiciones.seguimiento.editar', $valor->id) }}"
                                                                        class="btn-tablas" title="Editar">
                                                                        <button class="btn-tablas" title="Editar">
                                                                            <img src="{{ asset('img/btn_editar.png') }}"
                                                                                alt="Editar">
                                                                        </button>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('adquisicion.ver', $valor->id) }}"
                                                                        class="btn-tablas" title="Ver">
                                                                        <button class="btn-tablas" title="Ver">
                                                                            <img src="{{ 'img/btn_ver.jpeg' }}"
                                                                                alt="Image/png">
                                                                        </button>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                @if ($valor->id_estatus == 5 and Session::get('id_user') == $valor->emisor)
                                                                    <a href="{{ route('solicitudes.seguimiento.editar', $valor->id) }}"
                                                                        class="btn-tablas" title="Editar">
                                                                        <button class="btn-tablas" title="Editar">
                                                                            <img src="{{ asset('img/btn_editar.png') }}"
                                                                                alt="Editar">
                                                                        </button>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('solicitud.ver', $valor->id) }}"
                                                                        class="btn-tablas" title="Ver">
                                                                        <button class="btn-tablas" title="Ver">
                                                                            <img src="{{ 'img/btn_ver.jpeg' }}"
                                                                                alt="Image/png">
                                                                        </button>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </th>
                                                </tr>
                                @endforeach
                                </tbody>
                                </table>
                            </div>
                        @else
                            <h2 class="text-center font-bold mt-5">
                                No hay requerimientos en proceso.
                            </h2>
                            @endif
                            <div class="mt-5">
                                {{ $requerimientos->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
