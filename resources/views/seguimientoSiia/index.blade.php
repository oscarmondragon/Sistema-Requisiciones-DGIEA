<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Requisiciones enviadas al SIIA') }}
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
                        <th scope="col">id proyecto</th>
                        <th scope="col">id rubro</th>
                        <th scope="col">Ultima modificacion</th>        
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($requisiciones as $requisicion)
                      <tr>
                  <td>{{$loop->iteration}}</td>
                  <td> {{$requisicion->id}} </td>
                  <td>{{$requisicion->tipo_requisicion}}</td>
                  <td>{{$requisicion->id_proyecto}}</td>
                  <td>{{$requisicion->id_rubro_requis}}</td>
                  <td>{{$requisicion->updated_at}}</td>
                </tr>
               @if ($adquisiciones == '')
                  <h1>No hay requisiciones para mostrar</h1>  
               @endif
              @endforeach
            </tbody>
          </table>
    
                </div>
            </div>
        </div>
    </div>
  </x-app-layout>
  