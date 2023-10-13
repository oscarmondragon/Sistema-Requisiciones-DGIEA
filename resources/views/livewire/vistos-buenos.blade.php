<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 text-textos_generales">
        <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3>Requerimientos pendientes de enviar</h3>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 overflow-x-auto">
                <input type="text" wire:model="search" placeholder="Buscar por clave, tipo...">
                <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                  <thead>
                    <tr class="bg-blanco">
                      <th scope="col">#</th>
                      <th scope="col">Clave requerimiento</th>
                      <th scope="col">Rubro</th>
                      <th scope="col">Tipo requerimiento</th>
                      <th scope="col">Estatus</th>
                      <th scope="col">Ultima modificacion</th>
                      <th scope="col">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($adquisiciones as $adquisicion)
                    <tr class="border-b-gray-200 border-transparent">
                      <td> {{ $loop->iteration}}</td>
                      <td> {{ $adquisicion->clave_adquisicion}} </td>
                      <td> {{ $adquisicion->cuentas->nombre_cuenta }} </td>
                      <td> {{ $adquisicion->requerimiento->descripcion }} </td>
                      <td> {{ $adquisicion->estatus->descripcion }} </td>
                      <td> {{ $adquisicion->updated_at}}</td>
                      <td>
                        <a href="{{route('adquisiciones.editar', $adquisicion->id)}}" class="btn-tablas" title="Editar">
                          <img src="{{ asset('img/btn_editar.png') }}" alt="Editar">
                      </a>
                        <button type="button" wire:click="deleteAdquisicion({{ $adquisicion->id }})" class="btn-tablas" title="Eliminar">
                          <img src="{{ ('img/btn_eliminar.png') }}" alt="Image/png">
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  {{--   @foreach ($solicitudes as $solicitud)
                    <tr class="border-b-gray-200 border-transparent">
                      <td> {{ $loop->iteration }} </td>
                      <td> {{ $solicitud->clave_solicitud }} </td>
                      <td> {{ $solicitud->rubroSolicitud->nombre_cuenta }} </td>
                      <td> {{ $solicitud->requerimientoSolicitud->descripcion }} </td>
                      <td> {{ $solicitud->estatusSolicitud->descripcion }} </td>
                      <td> {{ $solicitud->updated_at}} </td>
                      <td>
                        <button type="button" class="btn-tablas" title="Editar">
                          <img src="{{ ('img/btn_editar.png') }}" alt="Image/png">
                        </button>
                        <button type="button" @click.stop="elementos.splice(index, 1); $wire.deleteBien(elemento)" class="btn-tablas" title="Eliminar">
                          <img src="{{ ('img/btn_eliminar.png') }}" alt="Image/png">
                        </button>
                      </td>
                    </tr>
                    @endforeach --}}
                  </tbody>
                </table>
                {{$adquisiciones->links()}}
              </div>
            </div>
          </div>
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3>Requerimientos pendientes de visto bueno </h3>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 ">
                <input type="text" wire:model="searchVobo" placeholder="Buscar por clave, tipo...">
                <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                  <thead>
                    <tr class="bg-blanco">
                      <th scope="col">#</th>
                      <th scope="col">Clave requerimiento</th>
                      <th scope="col">Rubro</th>
                      <th scope="col">Tipo requerimiento</th>
                      <th scope="col">VoBo RT</th>
                      <th scope="col">VoBo Administrativo</th>
                      <th scope="col">Estatus</th>
                      <th scope="col">Ultima modificacion</th>
                      <th scope="col">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($adquisicionesVistosBuenos as $adquisicion)
                    <tr class="border-b-gray-200 border-transparent">
                      <td> {{ $loop->iteration}}</td>
                      <td> {{ $adquisicion->clave_adquisicion}} </td>
                      <td> {{ $adquisicion->cuentas->nombre_cuenta}} </td>
                      <td> {{ $adquisicion->requerimiento->descripcion }} </td>
                      <td> {{ $adquisicion->vobo_rt }} </td>
                      <td> {{ $adquisicion->vobo_admin }} </td>
                      <td> {{ $adquisicion->estatus->descripcion }} </td>
                      <td> {{ $adquisicion->updated_at}}</td>
                      <th class="w-[148px]">
                        <button type="button" class="btn-primary">
                          Visto bueno
                        </button>
                      </th>
                    </tr>
                    @endforeach
                    {{-- @foreach ($solicitudes as $solicitud)
                    <tr class="border-b-gray-200 border-transparent">
                      <td> {{ $loop->iteration}}</td>
                      <td> {{ $solicitud->clave_solicitud}} </td>
                      <td> {{ $solicitud->rubroSolicitud->nombre_cuenta}} </td>
                      <td> {{ $solicitud->requerimientoSolicitud->descripcion }} </td>
                      <td> {{ $solicitud->vobo_rt }} </td>
                      <td> {{ $solicitud->vobo_admin }} </td>
                      <td> {{ $solicitud->estatusSolicitud->descripcion }} </td>
                      <td> {{ $solicitud->updated_at}}</td>
                      <th class="w-[148px]">
                        <button type="button" class="btn-primary">
                          Visto bueno
                        </button>
                      </th>
                    </tr>
                    @endforeach--}}
                  </tbody>
                </table>
                {{$adquisiciones->links()}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>