<div x-data class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-textos_generales">
                <div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div>
                           {{--  <img src="img/ic_req_pendientes.png" alt="Image/png" class="inline-block"> --}}
                            <h3 class="inline-block text-xl pl-2">Requerimientos en revisión</h3>
                        </div>
                        @if (session('success'))
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                            <script>
                                Swal.fire({
                                    position: 'top-center',
                                    icon: 'success',
                                    text: '{{ session('success') }}',
                                    confirmButtonText: 'Aceptar',
                                    confirmButtonColor: '#62836C',
                                    showConfirmButton: true,
                                    //timer: 2500
                                })
                            </script>
                        @endif
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <!-- <input type="text" wire:model="search" placeholder="Buscar por clave, tipo..." class="inputs-formulario-solicitudes"> -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="sm:w-2/3  w-full">
                                        <select class="sm:w-auto  w-full" id="categoria" name="categoria"
                                            wire:model="categoria">
                                            <option value="0">Todo</option>
                                            @foreach ($tipoRequisicion as $tipo)
                                                <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                                            @endforeach
                                            <option value="3">Pendientes de revisar</option>
                                            <option value="4">En DGIEA</option>
                                            <option value="5">En SIIA</option>
  
                                        </select>
                                        <input type="text" wire:model="search" onfocus="this.value=null"
                                            class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-full"
                                            placeholder="Buscar por clave, tipo...">
  
                                    </div>
  
                                    <div class="flex-1 md:mt-0 mt-2">
                                        <p class="text-verde font-semibold">Filtrar por fecha</p>
                                        <input type="date" name="f_inicial" id="f_inicial" wire:model="f_inicial"
                                            class="bg-blanco text-textos_generales rounded-md border-transparent h-10 sm:w-auto w-full">
                                        <input type="date" name="f_final" id="f_final" wire:model="f_final"
                                            class="bg-blanco text-textos_generales rounded-md border-transparent h-10 md:mt-0 mt-2 sm:w-auto w-full">
                                    </div>
                                </div>
                                @if ($requerimientos->first())
                                    <div class="overflow-x-auto">
                                        <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto mt-6">
                                            <thead>
                                                <tr class="bg-blanco">
                                                    <th class="w-[13%] cursor-pointer"
                                                        wire:click="sort('id_requerimiento')">
                                                        Clave requerimiento
                                                    </th>
                                                    <th class="w-[13%] cursor-pointer"
                                                    wire:click="sort('clave_siia')">
                                                    Clave SIIA
                                                </th>
                                                    <th class="w-[13%] cursor-pointer"
                                                    wire:click="sort('id_requerimiento')">
                                                    Concepto
                                                </th>
                                                    <th class="w-[13%] cursor-pointer"
                                                    wire:click="sort('id_requerimiento')">
                                                    Clave proyecto
                                                </th>
                                                    <th class="w-[30%] cursor-pointer"
                                                        wire:click="sort('nombre_cuenta')">
                                                        Rubro
                                                    </th>
                                                    <th class="w-[17%] cursor-pointer" wire:click="sort('descripcion')">
                                                        Tipo requerimiento
                                                    </th>
                                                    <th class="w-[13%] sm:text-center cursor-pointer"
                                                        wire:click="sort('estado')">
                                                        Estatus
                                                    </th>
                                                    <th class="w-[15%] cursor-pointer"
                                                        wire:click="sort('modificacion')">
                                                        Ultima modificación</th>
                                                    <th class="w-[12%]">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
  
                                                @foreach ($requerimientos as $requerimiento)
                                                    <tr class="border-b-gray-200 border-transparent">
                                                        <td> {{ $requerimiento->id_requerimiento }} </td>
                                                        <td> {{ $requerimiento->clave_siia }} </td>
                                                        <td>  {{ $requerimiento->concepto }} </td>  
                                                        <td> {{ $requerimiento->clave_proyecto }} </td>
                                                        <td> {{ $requerimiento->nombre_cuenta }} </td>
                                                        <td> {{ $requerimiento->descripcion }} </td>
                                                        <td class="sm:text-center">
                                                            @if ($requerimiento->id_estatus == 4)
                                                                <span
                                                                    class="bg-yellow-100 text-yellow-700 rounded-full p-1 px-2 font-bold text-center block mx-1">
                                                                    {{ $requerimiento->estado }}
                                                                </span>
                                                            @elseif($requerimiento->id_estatus == 5)
                                                                <span
                                                                    class="bg-red-100 text-red-500 rounded-full p-1 font-bold text-center block mx-1">
                                                                    {{ $requerimiento->estado }}
                                                                </span>
                                                            @elseif($requerimiento->id_estatus == 6)
                                                              <span
                                                              class="bg-green-100 text-green-700 rounded-full p-1 font-bold text-center block mx-1">
                                                              {{ $requerimiento->estado }}
                                                          </span>
                                                          @else
                                                          <span
                                                          class="bg-yellow-100 text-yellow-700 rounded-full p-1 px-2 font-bold text-center block mx-1">
                                                          {{ $requerimiento->estado }}
                                                          </span>
                                                          
                                                            @endif
                                                        </td>
                                                        <td> {{ $requerimiento->modificacion }}</td>
  
                                                        @if ($requerimiento->tipo_requerimiento == 1 )
                                                              @if(in_array($requerimiento->tipo_estado, [2]))
                                                                  <td>
                                                                      <a href="{{ route('adquisicion.revisar', ['id' => $requerimiento->id]) }}"
                                                                          title="Editar">
                                                                          @if ($requerimiento->id_estatus == 4)
                                                                          <button class="btn-primary sm:w-auto w-5/6" title="Revisar">
                                                                              Revisar
                                                                          </button>
                                                                          @else
                                                                          <button class="btn-primary sm:w-auto w-5/6" title="Revisar">
                                                                              Actualizar
                                                                          </button>
                                                                          @endif
                                                                      </a>
                                                                  </td>
                                                              @else
                                                                  <td>
                                                                      <a  href="{{ route('adquisicion.revisar', [$requerimiento->id, $requerimiento->id_requisicion_detalle]) }}"
                                                                          title="Revisar">
                                                                          <button class="btn-primary sm:w-auto w-5/6" title="Actualizar adqui">
                                                                            Actualizar  detalles adqui
                                                                          </button>
                                                                      </a>
                                                                  </td>
                                                              @endif
                                                      @elseif($requerimiento->tipo_requerimiento == 2)
                                                      <td>
                                                          <a  href="{{ route('solicitud.revisar', $requerimiento->id) }}"
                                                              title="Revisar">
                                                              @if ($requerimiento->id_estatus == 4)
                                                              <button class="btn-primary sm:w-auto w-5/6" title="Revisar">
                                                                  Revisar
                                                              </button>
                                                              @else
                                                              <button class="btn-primary sm:w-auto w-5/6" title="Actualizar">
                                                                  Actualizar
                                                              </button>
                                                              @endif
                                                          </a>
                                                      </td>
                                                      @endif
                                                    </tr>
                                                @endforeach
  
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <td colspan="6">
                                        <h2 class="text-center font-bold mt-5">No hay requerimientos pendientes de
                                            envío.</h2>
                                    </td>
                                @endif
                                <div class="mt-5">
                                    {{ $requerimientos->links() }}
                                </div>
                            </div>
                        </div>
          </div>