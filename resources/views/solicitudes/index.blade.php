<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Solicitudes de recursos') }}
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white  border-b border-gray-200  ">
                <table class="mb-4">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">id</th>
                      <th scope="col">Clave requi</th>
                      <th scope="col">id tipo</th>
                      <th scope="col">id proyecto</th>
                      <th scope="col">id rubro</th>
                      <th scope="col">Ultima modificacion</th>        
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($solicitudes as $solicitud)
                    <tr>
                <td>{{$loop->iteration}}</td>
                <td> {{$solicitud->id}} </td>
                <td> {{$solicitud->clave_solicitud}} </td>
                <td>{{$solicitud->tipo_solicitud}}</td>
                <td>{{$solicitud->id_proyecto}}</td>
                <td>{{$solicitud->id_rubro_requis}}</td>
                <td>{{$solicitud->updated_at}}</td>
              </tr>
             
            @endforeach
          </tbody>
        </table>
        {{ $solicitudes->links()}}
              </div>
          </div>
      </div>
  </div>
</x-app-layout>
