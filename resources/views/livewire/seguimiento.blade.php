
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <h3>Requerimientos enviados a DGIEA </h3>

          <br>
          <h1>¡Aqui van los FILTROS!</h1>
          <br>


                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 ">
                      <div class="inline-block w-full">
                        <select class="w-auto" id="categoria" name="categoria" wire:model="categoria"  @change="$wire.filterByCategory($event.target.selectedOptions[0].getAttribute('data-id-especial'))">
                          <option value="0">Todo</option>
                          @foreach ($tipoRequisicion as $tipo)
                          <option value="{{ $tipo->id }}" data-id-especial="{{ $tipo->id }}" >{{ $tipo->descripcion }}</option>
                          @endforeach
                        </select>
      
                        <div class="inline-block w-96">
                          <span>
                            <img src="{{ asset('img/ic_lock.png') }}" alt="Icono" class="absolute mr-10 ml-[345px] mt-1 w-8 h-8">
                          </span>
                          <input type="text" wire:model="search" class="inputs-formulario-solicitudes inline-block p-2.5 w-full" placeholder="Buscar por clave, tipo...">
                        </div>
      
                        <div class="inline-block ml-48">
                          <input type="date" name="f_inicial" id="f_inicial" wire:model="f_inicial"  class="bg-blanco text-textos_generales rounded-md border-transparent h-10">
                          <input type="date" name="f_final" id="f_final" wire:model="f_final" class="bg-blanco text-textos_generales rounded-md border-transparent h-10">
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
                      <td class="text-dorado"> {{$valor->estado}} </td>
                      <td>{{$valor->modificacion}}</td>
                      <td></td>
                      <th class="w-[148px]">
                        <button type="button" class="btn-primary">
                          Ver
                        </button>
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
                      <button type="button" class="btn-success">
                        Corregir
                      </button>
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
                      <button type="button" class="btn-primary">
                        Ver
                      </button>
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