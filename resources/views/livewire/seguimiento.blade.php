<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <h3>Requerimientos enviados a DGIEA </h3>
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
                                        <input type="text" wire:model="search"
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
                                            <th class="w-[18%]">Rubro</th>
                                            <th class="w-[15%]">Tipo requerimiento</th>
                                            <th class="w-[15%]">Estatus</th>
                                            <th class="w-[15%]">Ultima modificacion</th>
                                            <th class="w-[17%]">Observaciones</th>
                                            <th class="w-[8%]">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requerimientos as $adquisicion => $valor)
                                            @if($valor->estadoReq ==3 || $valor->estadoReq == 4 || $valor->estadoReq == 5)
                                                <tr class="border-b-gray-200 border-transparent">
                                                    <td> {{ $valor->id_requerimiento }} </td>
                                                    <td>{{ $valor->nombre_cuenta }}</td>
                                                    <td> {{ $valor->descripcion }}</td>
                                                    <td>
                                                    @if ($valor->estadoReq == 3 )
                                                      <span class="bg-dorado text-white text-center rounded-full p-1 px-2 font-bold inline-block">
                                                            {{ $valor->estado }}
                                                      </span>
                                                      @elseif($valor->estadoReq == 5)
                                                      <span class="bg-rojo text-white text-center rounded-full p-1 px-2 font-bold inline-block">
                                                            {{ $valor->estado }}
                                                      </span>
                                                      @elseif($valor->estadoReq == 6)
                                                      <span class="bg-verde text-white text-center rounded-full p-1 px-2 font-bold inline-block">
                                                            {{ $valor->estado }}
                                                      </span>
                                                      @else
                                                      <span class="bg-btn_cancelar text-white text-center rounded-full p-1 px-2 font-bold inline-block">
                                                            {{ $valor->estado }}
                                                      </span>
                                                    @endif
                                                    </td>
                                                    <td>{{ $valor->modificacion }}</td>
                                                    <td>
                                                    @foreach ($valor->detalless as $detalle )
                                                    {{ $detalle->descripcion }},
                                                    @endforeach
                                                    </td>
                                                    <th class="w-[148px]">
                                                    @php($count=0)
                                                    @foreach ($valor->detalless as $detalle )
                                                        @if($detalle->estatus_rt == null)
                                                            @php($count++)
                                                        @endif
                                                    @endforeach
                                                    @if($count>1)
                                                        <p>tiene mas de un detalle pendiente de envio a SG {{$count}}</p>
                                                    @endif
                                                    @if ($valor->tRequisicion == 1)  
                                                        @if ($valor->estadoReq == 5 and Session::get('id_user') == $valor->id_emisor)
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
                                                    @elseif($valor->tRequisicion == 2)
                                                        @if ($valor->estadoReq == 5 and Session::get('id_user') == $valor->id_emisor)
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
                                                @endif
                                            @endforeach                                            
                                        </tbody>
                                    </table>
                                </div>
                                {{ $requerimientos->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <h3>Requerimientos enviados a SIIA </h3>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 ">
                                <div class="flex flex-wrap items-center gap-2 mb-8">
                                    <div class="w-2/3">
                                        <select class="w-auto" id="categoria" name="categoria"
                                            wire:model="categoriaSIIA"
                                            @change="$wire.filterByCategory($event.target.selectedOptions[0].getAttribute('data-id-especial'))">
                                            <option value="0">Todo</option>
                                            @foreach ($tipoRequisicion as $tipo)
                                                <option value="{{ $tipo->id }}" data-id-especial="{{ $tipo->id }}">{{ $tipo->descripcion }} </option>
                                            @endforeach
                                        </select>
                                        <input type="text" wire:model="searchSIIA"
                                            class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-auto"
                                            placeholder="Buscar por clave, descripcion, estatus...">
                                    </div>
                                    <div class="flex-1 md:mt-0 mt-2">
                                        <p class="text-verde font-semibold">Filtrar por fecha</p>
                                        <input type="date" name="f_inicialSIIA" id="f_inicialSIIA" wire:model.live="f_inicialSIIA" class="bg-blanco text-textos_generales rounded-md border-transparent h-10 sm:w-auto w-full">
                                    <input type="date" name="f_finalSIIA" id="f_finalSIIA" wire:model.live="f_finalSIIA" class="bg-blanco text-textos_generales rounded-md border-transparent h-10 md:mt-0 mt-2 sm:w-auto w-full">
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                                    <thead>
                                        <tr class="bg-blanco">
                                            <th class="w-[12%]">Clave requerimiento</th>
                                            <th class="w-[18%]">Descripcion</th>
                                            <th class="w-[15%]">Clave SIIA</th>
                                            <th class="w-[15%]">Factura SIIA</th>
                                            <th class="w-[15%]">Importe cotizacion</th>
                                            <th class="w-[15%]">Estatus</th>
                                            <th class="w-[15%]">Ultima modificacion</th>
                                            <th class="w-[17%]">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($requerimientosSIIA as $adquisicionSIIA => $valor)
                                            @if($valor->estadoReq == 7 || $valor->estadoReq == 8 || $valor->estadoReq == 9
                                            || $valor->estadoReq == 10 || $valor->estadoReq == 11 || $valor->estadoReq == 12 
                                            || $valor->estadoReq == 13)
                                            @foreach ($valor->detalless as $detalle )
                                                <tr class="border-b-gray-200 border-transparent">
                                                    <td>{{ $valor->id_requerimiento }} </td>
                                                    <td>{{ $detalle->descripcion }}</td>
                                                    <td>{{ $detalle->clave_SIIA }}</td>
                                                    @if ($valor->tRequisicion == 1) 
                                                        <td>{{ $detalle->factura_SIIA }}</td>
                                                        <td>{{ $detalle->importe_cotizacion }}</td>
                                                        <td>
                                                            @if ($detalle->estatus_rt == 7 )
                                                            <span class="bg-dorado text-white text-center rounded-full p-1 px-2 font-bold inline-block">
                                                            {{ $detalle->estatus->descripcion }}
                                                            </span>
                                                            @elseif($detalle->estatus_rt == 8)
                                                            <span class="bg-rojo text-white text-center rounded-full p-1 px-2 font-bold inline-block">
                                                            {{ $detalle->estatus->descripcion }}
                                                            </span>
                                                            @elseif($detalle->estatus_rt == 9)
                                                            <span class="bg-verde text-white text-center rounded-full p-1 px-2 font-bold inline-block">
                                                            {{$detalle->estatus->descripcion}}
                                                            </span>                                                      
                                                            @endif
                                                        </td>
                                                        <td>{{$detalle->updated_at }}</td>
                                                        <td>{{$detalle->observaciones }}  </td>
                                                    @else
                                                        <td></td>
                                                        <td>{{ $detalle->importe }}</td>
                                                        <td>
                                                            @if ($valor->estado == 3 )
                                                            <span class="bg-dorado text-white text-center rounded-full p-1 px-2 font-bold inline-block">
                                                            {{ $valor->estado }}
                                                            </span>
                                                            @elseif($valor->estado == 5)
                                                            <span class="bg-rojo text-white text-center rounded-full p-1 px-2 font-bold inline-block">
                                                            {{ $valor->estado  }}
                                                            </span>
                                                            @elseif($valor->estado == 6)
                                                            <span class="bg-verde text-white text-center rounded-full p-1 px-2 font-bold inline-block">
                                                            {{ $valor->estado}}
                                                            </span>                                                      
                                                            @endif
                                                        </td>
                                                        <td>{{ $valor->modificacion }}</td>
                                                        <td>  {{ $valor->observaciones }}                        </td>
                                                    @endif
                                                    
                                                   
                                                </tr>
                                                @endforeach
                                                @endif
                                            @endforeach                                            
                                        </tbody>
                                    </table>
                                </div>
                                {{ $requerimientosSIIA->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
