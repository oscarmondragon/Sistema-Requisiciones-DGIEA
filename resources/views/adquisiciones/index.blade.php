<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Adquisiciones de bienes y servicios') }}
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 ">
                <table class="table-auto">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">id</th>
                      <th scope="col">id tipo</th>
                      <th scope="col">clave</th>
                      <th scope="col">id proyecto</th>
                      <th scope="col">id rubro</th>
                      <th scope="col">Ultima modificacion</th>        
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($adquisiciones as $adquisicion)
                    <tr>
                <td>{{$loop->iteration}}</td>
                <td> {{$adquisicion->id}} </td>
                <td> {{$adquisicion->clave_adquisicion}} </td>
                <td>{{$adquisicion->tipo_adquisicion}}</td>
                <td>{{$adquisicion->id_proyecto}}</td>
                <td>{{$adquisicion->id_rubro_requis}}</td>
                <td>{{$adquisicion->updated_at}}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{$adquisiciones->links()}}
              </div>
          </div>
      </div>
  </div>
</x-app-layout>
