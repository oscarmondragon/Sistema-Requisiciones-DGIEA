<div x-data class="py-12">
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
                  @foreach ($adquisiciones as $adquisicion=>$valor)                   
                      <tr class="border-b-gray-200 border-transparent">
                      <td> {{ $valor->id}}</td>
                      <td> {{ $valor->id_requerimiento}} </td>
                      <td> {{ $valor->nombre_cuenta }} </td>
                      <td> {{ $valor->descripcion }} </td>
                      <td> {{ $valor->estado }} </td>
                      <td> {{ $valor->modificacion}}</td>
                      @if(str_contains($valor->id_requerimiento, "ADQ")) 
                      <td>
                        <a href="{{route('adquisiciones.editar', $valor->id)}}" class="btn-tablas" title="Editar">
                          <img src="{{ asset('img/btn_editar.png') }}" alt="Editar">
                      </a>
                        <button type="button" @click="deleteConfirmation('{{$valor->id}}')" class="btn-tablas" title="Eliminar">
                          <img src="{{ ('img/btn_eliminar.png') }}" alt="Image/png">
                        </button>
                      </td>
                      @else
                      <td>
                      {{-- <a href="{{route('solicitudes.editar', $valor->id)}}" class="btn-tablas" title="Editar">
                          <img src="{{ ('img/btn_editar.png') }}" alt="Image/png">
                        </a>--}}
                        <button type="button" @click.stop="elementos.splice(index, 1); $wire.deleteBien(elemento)" class="btn-tablas" title="Eliminar">
                          <img src="{{ ('img/btn_eliminar.png') }}" alt="Image/png">
                        </button>
                      </td>
                      @endif
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
                    @foreach ($adquisicionesVistosBuenos as $adquisicionvobo=>$valorvobo)
                    
                    <tr class="border-b-gray-200 border-transparent">
                      <td> {{ $loop->iteration}}</td>
                      <td> {{ $valorvobo->id_requerimiento}} </td>
                      <td> {{ $valorvobo->nombre_cuenta}} </td>
                      <td> {{ $valorvobo->descripcion }} </td>
                      <td> {{ $valorvobo->vobo_rt }} </td>
                      <td> {{ $valorvobo->vobo_admin }} </td>
                      <td> {{ $valorvobo->estado}} </td>
                      <td> {{ $valorvobo->modificacion}}</td>
                      @if($valorvobo->getTable() == 'adquisiciones')
                      <th class="w-[148px]">
                        @if(Session::get('id_user') != $valorvobo->id_emisor)
                        <button type="button" class="btn-primary">
                          Visto bueno
                        </button>
                        @endif
                      </th>
                      @else
                      <th class="w-[148px]">
                        @if(Session::get('id_user') != $valorvobo->id_emisor)
                        <button type="button" class="btn-primary">
                          Visto bueno
                        </button>
                        @endif
                      </th>
                      @endif
                    </tr>  
                    @endforeach
                  </tbody>
                </table>
                {{$adquisicionesVistosBuenos->links()}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function deleteConfirmation(adquisicionId) {
        // Now you can access the event object (e) directly
     
        Swal.fire({
    title: '¿Estas seguro de eliminarlo?',
    text: 'Un requerimiento eliminado no se puede recuperar.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: '¡Si, eliminar requerimiento!',
    cancelButtonText: 'Cancelar',

  }).then((result) => {
    if (result.isConfirmed) {
      // Manda llamar el metodo liveware
      window.livewire.emit('deleteAdquisicion',adquisicionId);
      Swal.fire(
        'Se eliminó el requerimiento',
        'Eliminado correctamente',
        'success'
      )
    }
  });    }
</script>

@endpush
</div>


