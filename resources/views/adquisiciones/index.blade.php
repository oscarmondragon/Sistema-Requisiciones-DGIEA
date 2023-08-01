<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-200 leading-tight">
          {{ __('Adquisiciones de bienes y servicios') }}
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class=" p-20 items-center">
                <table class="table-auto text-center mb-5 ">
                  <thead class="bg-lime-700 text-white ">
                    <tr class="">
                      <th scope="col" class="w-1/12">#</th>
                      <th scope="col" class="w-1/12">id</th>
                      <th scope="col" class="w-2/12">id tipo</th>
                      <th scope="col"class="w-2/12">clave</th>
                      <th scope="col" class="w-2/12">id proyecto</th>
                      <th scope="col" class="w-1/12">id rubro</th>
                      <th scope="col" class="w-4/12">Ultima modificacion</th>        
                    </tr>
                  </thead>
                  <tbody class="">
                    @foreach ($adquisiciones as $adquisicion)
                    <tr class ="bg-gray-100">
                        <td class="w-1/12">{{$loop->iteration}}</td>
                        <td class="w-1/12"> {{$adquisicion->id}} </td>
                        <td class="w-2/12"> {{$adquisicion->clave_adquisicion}} </td>
                        <td class="w-2/12">{{$adquisicion->tipo_adquisicion}}</td>
                        <td class="w-2/12">{{$adquisicion->id_proyecto}}</td>
                        <td class="w-1/12">{{$adquisicion->id_rubro_requis}}</td>
                        <td class="w-4/12">{{$adquisicion->updated_at}}</td>
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
