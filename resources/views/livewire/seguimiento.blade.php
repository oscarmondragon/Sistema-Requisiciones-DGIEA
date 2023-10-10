
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <h3>Requerimientos enviados a DGIEA </h3>

          <br>
          <h1>¡Aqui van los filtros FILTROS!</h1>
          <br>


                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 ">
                      <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                        <thead>
                          <tr class="bg-blanco">
                            <th scope="col">#</th>
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
                          @foreach ($adquisiciones as $adquisicion)
                          <tr class="border-b-gray-200 border-transparent">
                      <td>{{$loop->iteration}}</td>
                      <td> {{$adquisicion->clave_adquisicion}} </td>
                      <td>SEMILLA </td>
                      <td> Adquisicion </td>
                      <td class="text-dorado"> En revision DGIEA </td>
                      <td>{{$adquisicion->updated_at}}</td>
                      <td></td>
                      <th class="w-[148px]">
                        <button type="button" class="btn-primary">
                          Ver
                        </button>
                      </th>
                    </tr>
                  @endforeach
                  <tr class="border-b-gray-200 border-transparent">
                    <td>50</td>
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
                    <td>51</td>
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
                  <tr class="border-b-gray-200 border-transparent">
                    <td>51</td>
                    <td>20231007ADQ52</td>
                    <td>COMBUSTIBLE </td>
                    <td> Solicitud </td>
                    <td class="text-dorado"> En revisión DGIEA </td>
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
              {{$adquisiciones->links()}}
                    </div>
                </div>
            </div>
        </div>
        </div>
      </div>
    </div>
  </div>