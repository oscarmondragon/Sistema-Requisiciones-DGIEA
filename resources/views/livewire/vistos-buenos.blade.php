<div x-data class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-textos_generales">
                <div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div>
                            <img src="img/ic_req_pendientes.png" alt="Image/png" class="inline-block">
                            <h3 class="inline-block text-xl pl-2">Requerimientos pendientes de enviar</h3>
                        </div>
                        @if (session('success'))
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                            <script>
                                Swal.fire({
                                    position: 'top-center',
                                    icon: 'success',
                                    text: '{{ session('success') }}',
                                    confirmButtonText: 'Aceptar!',
                                    confirmButtonColor: '#62836C',
                                    showConfirmButton: true,
                                    //timer: 2500
                                })
                            </script>
                        @endif
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 overflow-x-auto">
                                <!-- <input type="text" wire:model="search" placeholder="Buscar por clave, tipo..." class="inputs-formulario-solicitudes"> -->
                <div class="flex flex-wrap items-center gap-2">
                  <div class="sm:w-2/3  w-full">
                          <select class="sm:w-auto  w-full" id="categoria" name="categoria" wire:model="categoria"  @change="$wire.filterByCategory($event.target.selectedOptions[0].getAttribute('data-id-especial'))">
                            <option value="0">Todo</option>
                            @foreach ($tipoRequisicion as $tipo)
                            <option value="{{ $tipo->id }}" data-id-especial="{{ $tipo->id }}" >{{ $tipo->descripcion }}</option>
                            @endforeach
                          </select>
                      </select>
                      <input type="text" wire:model="search"
                          class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-full"
                          placeholder="Buscar por clave, tipo...">
                  </div>

                  <div class="flex-1 md:mt-0 mt-2">
                   <p class="text-verde font-semibold">Filtrar por fecha</p>
                    <input type="date" name="f_inicial" id="f_inicial" wire:model="f_inicial" class="bg-blanco text-textos_generales rounded-md border-transparent h-10 sm:w-auto w-full">
                    <input type="date" name="f_final" id="f_final" wire:model="f_final" class="bg-blanco text-textos_generales rounded-md border-transparent h-10 md:mt-0 mt-2 sm:w-auto w-full">
                  </div>
              </div>
                <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto mt-6">
                  <thead>
                    <tr class="bg-blanco">
                      <th class="w-[15%]" scope="col">Clave requerimiento</th>
                      <th class="w-[23%]" scope="col">Rubro</th>
                      <th class="w-[20%]" scope="col">Tipo requerimiento</th>
                      <th class="w-[10%]" scope="col">Estatus</th>
                      <th class="w-[20%]" scope="col">Ultima modificacion</th>
                      <th class="w-[12%]" scope="col">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>

                                        @foreach ($adquisiciones as $adquisicion => $valor)
                                            <tr class="border-b-gray-200 border-transparent">
                                                <td> {{ $valor->id_requerimiento }} </td>
                                                <td> {{ $valor->nombre_cuenta }} </td>
                                                <td> {{ $valor->descripcion }} </td>
                                                <td><span class="bg-yellow-200 rounded-full p-1 px-2 font-bold">
                                                        {{ $valor->estado }}
                                                </td>
                                                <td> {{ $valor->modificacion }}</td>
                                                @if (str_contains($valor->id_requerimiento, 'ADQ'))
                                                    <td>
                                                        <a href="{{ route('adquisiciones.editar', $valor->id) }}"
                                                            class="btn-tablas" title="Editar">
                                                            <button class="btn-tablas" title="Editar">
                                                                <img src="{{ asset('img/btn_editar.png') }}"
                                                                    alt="Editar">
                                                            </button>
                                                        </a>
                                                        <button type="button"
                                                            @click="deleteConfirmation('{{ $valor->id }}')"
                                                            class="btn-tablas" title="Eliminar">
                                                            <img src="{{ 'img/btn_eliminar.png' }}" alt="Image/png">
                                                        </button>
                                                    </td>
                                                @else
                                                    <td>
                                                        <a href="{{ route('solicitudes.editar', $valor->id) }}"
                                                            title="Editar">
                                                            <button class="btn-tablas" title="Editar">
                                                                <img src="{{ 'img/btn_editar.png' }}" alt="Image/png">
                                                            </button>
                                                        </a>
                                                        <button type="button"
                                                            @click="deleteSolicitud('{{ $valor->id }}')"
                                                            class="btn-tablas" title="Eliminar">
                                                            <img src="{{ 'img/btn_eliminar.png' }}" alt="Image/png">
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $adquisiciones->links() }}
                            </div>
                        </div>
                    </div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div>
                            <img src="img/ic_req_vobo.png" alt="Image/png" class="inline-block">
                            <h3 class="inline-block text-xl pl-2 mt-8">Requerimientos pendientes de visto bueno</h3>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 ">
                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="sm:w-2/3 w-full">
                                        <select class="sm:w-auto w-full" id="categoriaVobo" name="categoriaVobo"
                                            wire:model="categoriaVobo"
                                            @change="$wire.filterByCategoryVobo($event.target.selectedOptions[0].getAttribute('data-id-especial'))">
                                            <option value="0">Todo</option>
                                            @foreach ($tipoRequisicion as $tipo)
                                                <option value="{{ $tipo->id }}"
                                                    data-id-especial="{{ $tipo->id }}">{{ $tipo->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="text" wire:model="searchVobo"
                                            class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-full"
                                            placeholder="Buscar por clave, tipo...">
                                    </div>

                                    <div class="flex-1 md:mt-0 mt-2">
                                          <p class="text-verde font-semibold">Filtrar por fecha</p>
                                        <input type="date" name="f_inicial_vobo" id="f_inicial_vobo" wire:model="f_inicial_vobo"
                                            class="bg-blanco text-textos_generales rounded-md border-transparent h-10 sm:w-auto w-full">
                                        <input type="date" name="f_final_vobo" id="f_final_vobo" wire:model="f_final_vobo"
                                            class="bg-blanco text-textos_generales rounded-md border-transparent h-10 md:mt-0 mt-2 sm:w-auto w-full">
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto mt-6">
                                        <thead>
                                            <tr class="bg-blanco">
                                                <th class="w-[10%]" scope="col">Clave requerimiento</th>
                                                <th class="w-[18%]" scope="col">Rubro</th>
                                                <th class="w-[12%]" scope="col">Tipo requerimiento</th>
                                                <th class="w-[10%]" scope="col">VoBo RT</th>
                                                <th class="w-[10%]" scope="col">VoBo Administrativo</th>
                                                <th class="w-[15%] text-center" scope="col">Estatus</th>
                                                <th class="w-[15%]" scope="col">Ultima modificacion</th>
                                                <th class="w-[10%]" scope="col">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($adquisicionesVistosBuenos as $adquisicionvobo => $valorvobo)
                                                <tr class="border-b-gray-200 border-transparent">
                                                    <td> {{ $valorvobo->id_requerimiento }} </td>
                                                    <td> {{ $valorvobo->nombre_cuenta }} </td>
                                                    <td> {{ $valorvobo->descripcion }} </td>
                                                    <td>
                                                        @isset($valorvobo->vobo_rt)
                                                            {{ $valorvobo->vobo_rt }}
                                                        @else
                                                            <span class="text-rojo font-bold">Pendiente</span>
                                                        @endisset
                                                    </td>
                                                    <td> 
                                                      @isset($valorvobo->vobo_admin)
                                                          {{ $valorvobo->vobo_admin }}
                                                      @else
                                                        <span class="text-rojo font-bold">Pendiente</span>
                                                      @endisset
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="block mx-4 bg-btn_vobo/70 rounded-full p-1 px-2 text-center text-white font-bold">
                                                            {{ $valorvobo->estado }}
                                                        </span>
                                                    </td>
                                                    <td> {{ $valorvobo->modificacion }}</td>
                                                    @if (str_contains($valorvobo->id_requerimiento, 'ADQ'))
                                                        <th class="w-[148px]">
                                                            @if (Session::get('id_user') != $valorvobo->id_emisor)
                                                                <a href="{{ route('adquisicion.vobo', $valorvobo->id) }}"
                                                                    class="btn-tablas" title="Dar visto bueno">
                                                                    <button class="btn-tablas" title="Visto bueno">
                                                                        <img src="{{ '/img/btn_vobo.png' }}"
                                                                            alt="Image/png" title="Dar visto bueno">
                                                                    </button>
                                                                </a>
                                                            @endif
                                                        </th>
                                                    @else
                                                        <th class="w-[148px]">
                                                            @if (Session::get('id_user') != $valorvobo->id_emisor)
                                                                <a href="{{ route('solicitud.vobo', $valorvobo->id) }}"
                                                                    class="btn-tablas" title="Dar visto bueno">
                                                                    <button class="btn-tablas" title="Visto bueno">
                                                                        <img src="{{ '/img/btn_vobo.png' }}"
                                                                            alt="Image/png" title="Dar visto bueno">
                                                                    </button>
                                                                </a>
                                                            @endif
                                                        </th>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $adquisicionesVistosBuenos->links() }}
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
                    title: '¿Estás seguro de eliminarlo?',
                    text: 'Un requerimiento eliminado no se puede recuperar.',
                    icon: 'warning',
                    iconColor: '#9D9361',
                    showCancelButton: true,
                    confirmButtonColor: '#E86562',
                    cancelButtonColor: '#62836C',
                    confirmButtonText: 'Si, eliminar',
                    cancelButtonText: 'Cancelar',

                }).then((result) => {
                    if (result.isConfirmed) {
                        // Manda llamar el metodo liveware
                        window.livewire.emit('deleteAdquisicion', adquisicionId);
                        Swal.fire({
                            icon: 'success',
                            confirmButtonText: 'Aceptar!',
                            confirmButtonColor: '#62836C',
                            title: 'Se eliminó correctamente el requerimiento',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }

            function deleteSolicitud(solicitudId) {
                // Now you can access the event object (e) directly
                Swal.fire({
                    title: '¿Estás seguro de eliminarlo?',
                    text: 'Un requerimiento eliminado no se puede recuperar.',
                    icon: 'warning',
                    iconColor: '#9D9361',
                    showCancelButton: true,
                    confirmButtonColor: '#E86562',
                    cancelButtonColor: '#62836C',
                    confirmButtonText: 'Si, eliminar',
                    cancelButtonText: 'Cancelar',

                }).then((result) => {
                    if (result.isConfirmed) {
                        // Manda llamar el metodo liveware
                        window.livewire.emit('deleteSolicitud', solicitudId);
                        Swal.fire({
                            icon: 'success',
                            confirmButtonText: 'Aceptar!',
                            confirmButtonColor: '#62836C',
                            title: 'Se eliminó correctamente el requerimiento',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }
        </script>
    @endpush
</div>
