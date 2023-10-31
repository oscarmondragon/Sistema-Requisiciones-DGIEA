
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <h3>Requerimientos enviados a DGIEA </h3>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 ">
                      <div class="flex flex-wrap items-center gap-2 mb-8">
                        <div class="w-2/3">
                            <select class="w-auto" id="categoriaVobo" name="categoriaVobo" wire:model="categoriaVobo"  @change="$wire.filterByCategoryVobo($event.target.selectedOptions[0].getAttribute('data-id-especial'))">
                          <option value="0">Todo</option>
                          @foreach ($tipoRequisicion as $tipo)
                          <option value="{{ $tipo->id }}" data-id-especial="{{ $tipo->id }}" >{{ $tipo->descripcion }}</option>
                          @endforeach
                        </select>
                            <input type="text" wire:model="search"
                                class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-auto"
                                placeholder="Buscar por clave, tipo...">
                        </div>
      
                        <div class="flex-1 md:mt-0 mt-2">
                            <p class="text-verde font-semibold">Filtrar por fecha</p>
                            <input type="date" name="" id=""
                                class="bg-blanco text-textos_generales rounded-md border-transparent h-10">
                            <input type="date" name="" id=""
                                class="bg-blanco text-textos_generales rounded-md border-transparent h-10 md:mt-0 mt-2">
                        </div>
                    </div>
                      <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                        <thead>
                          <tr class="bg-blanco">
                            <th scope="col">Clave requerimiento</th>
                            <th scope="col">Rubro</th>
                            <th scope="col">Tipo requerimiento</th>
                            <th scope="col">Estatus</th>
                            <th scope="col">Ultima modificacion</th>        
                            <th scope="col">Observaciones</th>        
                            <th scope="col">Acciones</th>        
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($requerimientos as $adquisicion=>$valor)
                          <tr class="border-b-gray-200 border-transparent">
                      <td> {{$valor->id_requerimiento}} </td>
                      <td>{{$valor->nombre_cuenta}}</td>
                      <td> {{$valor->descripcion }}</td>
                      <td><span class="bg-dorado text-white rounded-full p-1 px-2 font-bold">
                        {{ $valor->estado }}
                       </td>
                      <td>{{$valor->modificacion}}</td>
                      <td></td>
                      <th class="w-[148px]">
                        <a href=""  title="Ver">
                          <button class="btn-tablas" title="Ver">
                            <img src="{{ ('img/btn_ver.jpeg') }}" alt="Image/png">
                          </button>
                          </a>
                      </th>
                    </tr>
                  @endforeach
                  <tr class="border-b-gray-200 border-transparent">
                    <td> 20231006ADQ50</td>
                    <td>SEMILLA </td>
                    <td> Adquisicion </td>
                    <td class="text-rojo"> Rechazado </td>
                    <td>2023-10-06 14:22:59</td>
                    <td>No coiciden cotizaciones</td>
                    <th class="w-[148px]">
                      <a href="{{route('adquisiciones.editar', $valor->id)}}"  title="Editar">
                        <button class="btn-tablas" title="Editar">
                          <img src="{{ ('img/btn_editar.png') }}" alt="Image/png">
                        </button>
                        </a>
                    </th>
                  </tr>
                  <tr class="border-b-gray-200 border-transparent">
                    <td>20231006ADQ51</td>
                    <td>SEMILLA </td>
                    <td> Adquisicion </td>
                    <td class="text-verde"> Aceptado </td>
                    <td>2023-10-06 14:22:59</td>
                    <td></td>
                    <th class="w-[148px]">
                      <a href=""  title="Ver">
                        <button class="btn-tablas" title="Ver">
                          <img src="{{ ('img/btn_ver.jpeg') }}" alt="Image/png">
                        </button>
                        </a>
                    </th>
                  </tr>
                </tbody>
              </table>
              {{$requerimientos->links()}}
                    </div>
                </div>
            </div>
        </div>
        </div>
      </div>
    </div>
  </div>