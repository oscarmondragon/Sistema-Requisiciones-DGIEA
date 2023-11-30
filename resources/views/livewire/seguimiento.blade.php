<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <h3>Requerimientos en proceso </h3>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 ">
                                <div class="flex flex-wrap items-center gap-2 mb-8">
                                    <div class="w-2/3">
                                        <select class="w-auto" id="categoria" name="categoria"
                                            wire:model="categoria"
                                            @change="$wire.filterByCategory($event.target.selectedOptions[0].getAttribute('data-id-especial'))">
                                            <option value="0">Todo</option>
                                            @foreach ($tipoRequisicion as $tipo)
                                                <option value="{{ $tipo->id }}" data-id-especial="{{ $tipo->id }}">{{ $tipo->descripcion }} </option>
                                            @endforeach
                                        </select>
                                        <input type="text" wire:model.live="search"
                                            class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-auto"
                                            placeholder="Buscar por clave, tipo...">
                                    </div>
                                    <div class="flex-1 md:mt-0 mt-2">
                                        <p class="text-verde font-semibold">Filtrar por fecha</p>
                                        <input type="date" name="f_inicial" id="f_inicial" wire:model.live="f_inicial" class="bg-blanco text-textos_generales rounded-md border-transparent h-10 sm:w-auto w-full">
                                    <input type="date" name="f_final" id="f_final" wire:model.live="f_final" class="bg-blanco text-textos_generales rounded-md border-transparent h-10 md:mt-0 mt-2 sm:w-auto w-full">
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                                    <thead>
                                        <tr class="bg-blanco">
                                            <th class="w-[12%]">Clave requerimiento</th>
                                            <th class="w-[12%]">Clave SIIA</th>
                                            <th class="w-[18%]">Rubro</th>
                                            <th class="w-[18%]">descripcion</th>
                                            <th class="w-[15%]">Tipo requerimiento</th>
                                            <th class="w-[15%]">Estatus</th>
                                            <th class="w-[15%]">Ultima modificacion</th>
                                            <th class="w-[17%]">Observaciones</th>
                                            <th class="w-[8%]">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        @foreach ($requerimientos as $adquisicion => $valor)
                                       
                                        <td>  {{$valor->id_requerimiento}}</td>
                                        <td>  {{$valor->claveSIIA}}</td>
                                        <td>  {{$valor->nombre_cuenta}}</td>
                                        <td>  {{$valor->concepto}}</td>
                                        <td>  {{$valor->req}}</td>
                                        <td class="sm:text-center">
                                                @if ( str_contains($valor->id_estatus, '4'))
                                                    <span
                                                         class="bg-yellow-100 text-yellow-700 rounded-full p-1 px-2 font-bold text-center block mx-1">
                                                         {{ $valor->estado }}
                                                    </span>
                                                @elseif (str_contains($valor->estado, 'Envia'))
                                                    <span
                                                         class="bg-green-100 text-green-700 rounded-full p-1 px-2 font-bold text-center block mx-1">
                                                         {{ $valor->estado }}
                                                    </span>
                                                @elseif(str_contains($valor->estado, 'Rechaz'))
                                                    <span
                                                         class="bg-red-100 text-red-500 rounded-full p-1 font-bold text-center block mx-1">
                                                         {{ $valor->estado }}
                                                    </span>
                                                @elseif(str_contains($valor->estado, 'Acepta'))
                                                    <span
                                                         class="bg-green-600 text-white rounded-full p-1 font-bold text-center block mx-1">
                                                         {{ $valor->estado }}
                                                    </span>
                                                @endif
                                        </td>
                                       

                                        <td>  {{$valor->modificacion}}</td>
                                        <td>  {{$valor->observaciones}}</td>
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
                                                                <img src="{{ 'img/btn_ver.jpeg' }}" alt="Image/png">
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
                                                                <img src="{{ 'img/btn_ver.jpeg' }}" alt="Image/png">
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
                                {{ $requerimientos->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
