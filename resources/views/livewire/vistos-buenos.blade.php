<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 text-gray-900">
        <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h3>Requerimientos incompletos </h3>

              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6 ">
                    <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                      <thead>
                        <tr class="bg-blanco">
                          <th scope="col">#</th>
                          <th scope="col">Id</th>
                          <th scope="col">Clave requerimiento</th>
                          <th scope="col">Tipo requerimiento</th>
                          <th scope="col">Clave proyecto</th>
                          <th scope="col">id rubro</th>
                          <th scope="col">Ultima modificacion</th>        
                          <th scope="col">Acciones</th>        
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($adquisiciones as $adquisicion)
                        <tr class="border-b-gray-200 border-transparent">
                    <td>{{$loop->iteration}}</td>
                    <td> {{$adquisicion->id}} </td>
                    <td> {{$adquisicion->clave_adquisicion}} </td>
                    <td> Adquisicion </td>
                    <td>{{$adquisicion->clave_proyecto}}</td>
                    <td>{{$adquisicion->id_rubro}}</td>
                    <td>{{$adquisicion->updated_at}}</td>
                    <th class="w-[148px]">
                      <button type="button" class="btn-tablas">
                        <img src="{{ ('img/btn_editar.png') }}" alt="Image/png">
                      </button>
                      <button type="button" @click.stop="elementos.splice(index, 1); $wire.deleteBien(elemento)" class="btn-tablas">
                        <img src="{{ ('img/btn_eliminar.png') }}" alt="Image/png">
                      </button>
                    </th>
                  </tr>
                @endforeach
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
                        <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                          <thead>
                            <tr class="bg-blanco">
                              <th scope="col">#</th>
                              <th scope="col">Id</th>
                              <th scope="col">Clave requerimiento</th>
                              <th scope="col">Tipo requerimiento</th>
                              <th scope="col">Clave proyecto</th>
                              <th scope="col">id rubro</th>
                              <th scope="col">Ultima modificacion</th>     
                              <th scope="col">Acciones</th>        
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($adquisiciones as $adquisicion)
                            <tr class="border-b-gray-200 border-transparent">
                        <td>{{$loop->iteration}}</td>
                        <td> {{$adquisicion->id}} </td>
                        <td> {{$adquisicion->clave_adquisicion}} </td>
                        <td> Adquisicion </td>
                        <td>{{$adquisicion->clave_proyecto}}</td>
                        <td>{{$adquisicion->id_rubro}}</td>
                        <td>{{$adquisicion->updated_at}}</td>
                        <th class="w-[148px]">
                          <button type="button" class="btn-primary ">
                            Visto bueno
                          </button>
                        </th>
                      </tr>
                    @endforeach
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