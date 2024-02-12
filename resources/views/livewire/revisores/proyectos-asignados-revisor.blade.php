<div x-data>
  <x-slot name="header">
      <h2 class="font-semibold text-xl leading-tight ml-4">
          {{ __('Tus proyectos asignados') }}
      </h2>
  </x-slot>
  <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                      <h3 class="mt-5 ml-4">Filtros:</h3>
                          <div>
                              @if (auth()->user()->rol === 4)
                                  <div class="mb-6">
                                      <label for="idConvocatoriaAsignados" class="block">Convocatoria:</label>
                                      <select class="sm:w-3/4 w-full" id="idConvocatoriaAsignados"
                                          name="idConvocatoriaAsignados" wire:model="idConvocatoriaAsignados">
                                          <option value="0">Todas</option>
                                          @foreach ($convocatorias as $convocatoria)
                                              <option value="{{ $convocatoria['CveEntCnv'] }}"
                                                  title="{{ $convocatoria['Tipo_Proyecto'] }} - {{ $convocatoria['NomEntCnv'] }}">
                                                  {{ $convocatoria['Tipo_Proyecto'] }} -
                                                  {{ $convocatoria['NomEntCnv'] }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                              @endif


                              <div class="sm:grid sm:grid-cols-2 gap-x-8 mt-0">
                                  @if (auth()->user()->rol === 3)
                                      <div class="flex-col">
                                          <label for="idTipoProyectoAsignados">Tipo de proyecto:</label>
                                          <select class="sm:w-full w-full mt-1" id="idTipoProyectoAsignados"
                                              name="idTipoProyectoAsignados" wire:model="idTipoProyectoAsignados">
                                              <option value="0">Todos</option>
                                              @foreach ($tipoProyectos as $tipo)
                                                  <option value="{{ $tipo['Tipo_Proyecto'] }}">
                                                      {{ $tipo['Tipo_Proyecto'] }}</option>
                                              @endforeach
                                          </select>
                                      </div>
                                  @endif

                                  <div class="flex-col">
                                      <label for="idEspacioAcademicoAsignados">
                                          Espacio académico:
                                      </label>
                                      <select class="w-full mt-1" id="idEspacioAcademicoAsignados"
                                          name="idEspacioAcademicoAsignados"
                                          wire:model="idEspacioAcademicoAsignados">
                                          <option value="0">Todos</option>
                                          @foreach ($espaciosAcademicos as $espacioAcademico)
                                              <option value="{{ $espacioAcademico['CveCenCos'] }}">
                                                  {{ $espacioAcademico['CveCenCos'] }} -
                                                  {{ $espacioAcademico['NomCenCos'] }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                              </div>

                              <div class="sm:grid sm:grid-cols-3 items-center gap-x-1 mt-6 sm:justify-between">
                                  <div class="flex-col sm:mt-0 mt-4">
                                      <label for="buscar">Buscar:</label>
                                      <input type="text" id="buscar" wire:model="search"
                                          class="inputs-formulario-solicitudes sm:w-[315px] w-full"
                                          placeholder="Buscar por clave del proyecto, nombre...">
                                  </div>

                                  <div class="flex-col sm:ml-20">
                                      <button type="button" class="bg-blue-600 sm:w-auto w-full sm:mt-0 mt-4"
                                          wire:click="limpiarFiltros">
                                          Limpiar filtros
                                      </button>
                                  </div>
                              </div>


                              <div class="mt-9">
                                  <h3>Proyectos asignados</h3>
                              </div>
                              <div class="overflow-x-auto mt-7">
                                  @if ($proyectosAsignados->first())
                                      <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                                          <thead>
                                              <tr class="bg-blanco">
                                                  <th class="w-[10%] cursor-pointer"
                                                      wire:click="sort('clave_uaem')">
                                                      Clave del proyecto
                                                      <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                  </th>
                                                  <th class="w-[25%] cursor-pointer"
                                                      wire:click="sort('nombre_proyecto')">
                                                      Nombre
                                                      <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                  </th>
                                                  <th class="w-[15%] cursor-pointer"
                                                      wire:click="sort('espacio_academico')">
                                                      Espacio académico
                                                      <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                  </th>
                                                  <th class="w-[17%] cursor-pointer"
                                                      wire:click="sort('fecha_final')">
                                                      Vigencia proyecto
                                                      <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                  </th>
                                                  <th class="w-[15%] cursor-pointer"
                                                  wire:click="sort('fecha_limite_adquisiciones')">
                                                  Limite adquisiciones
                                                  <span class="pl-1 text-verde font-bold">&#8645;</span>
                                              </th>
                                              <th class="w-[15%] cursor-pointer"
                                                  wire:click="sort('fecha_limite_solicitudes')">
                                                  Limite solicitudes
                                                  <span class="pl-1 text-verde font-bold">&#8645;</span>
                                              </th>
                                              <th class="w-[7%] cursor-pointer"
                                                  wire:click="sort('fecha_final')">
                                                  <span class="pl-1 text-verde font-bold">&#8645;</span>
                                              </th>
                                                  <th class="w-[10%]">Acciones</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              @foreach ($proyectosAsignados as $proyecto)
                                                  <tr class="border-b-gray-200 border-transparent">
                                                      {{-- <td> {{ $proyecto->CveEntPry }} </td> --}}
                                                      <td> 
                                                          {{ $proyecto->clave_digcyn == null ? $proyecto->clave_uaem : $proyecto->clave_digcyn }}
                                                      </td>
                                                      <td> {{ $proyecto->nombre_proyecto }}</td>
                                                      <td> {{ $proyecto->espacio_academico }}</td>
                                                      <td> <span class="text-verde">{{ $proyecto->fecha_inicio }} </span> @if($proyecto->fecha_inicio || $proyecto->fecha_final)<span class="font-bold">&#129046;</span>@endif  <span class="text-verde">{{ $proyecto->fecha_final }} </span></td>
                                                      <td> {{ $proyecto->fecha_limite_adquisiciones }}</td>
                                                      <td> {{ $proyecto->fecha_limite_solicitudes }}</td>
                                                      @if (($fechaHoy->diffInMonths($proyecto->fecha_final, false)) < 1)
                                                      <td class="text-center"> <span class="bg-red-500 h-5 w-5 rounded-full block"> </td>
                                                      @elseif(($fechaHoy->diffInMonths($proyecto->fecha_final, false)) < 2)
                                                      <td class="text-center"> <span class="bg-yellow-400 h-5 w-5 rounded-full block"> </td>
                                                      @else
                                                      <td class="text-center"> <span class="bg-green-500 h-5 w-5 rounded-full block"> </td>
                                                    @endif
                                                      <th class="w-[148px] text-center">
                                                          <button class="btn-tablas" type="button"
                                                              x-on:click="$wire.emit('openModal', 'revisores.editar-fechas-proyecto-modal', { 
                                                              'id_proyecto': '{{ $proyecto->id_proyecto }}', 
                                                              'clave_uaem': '{{ $proyecto->clave_uaem }}', 
                                                              'fecha_inicio': '{{ $proyecto->fecha_inicio }}', 
                                                              'fecha_final': '{{ $proyecto->fecha_final }}',
                                                              'fecha_limite_adquisiciones': '{{ $proyecto->fecha_limite_adquisiciones }}',
                                                              'fecha_limite_solicitudes': '{{ $proyecto->fecha_limite_solicitudes }}',
                                                            })"
                                                              title="Editar">
                                                              <img src="{{'img/botones/btn_editar.png'}}" alt="Editar">
                                                          </button>
                                                      </th>
                                                  </tr>
                                              @endforeach
                                          </tbody>
                                      </table>

                                      <div class="mt-10">
                                          {{ $proyectosAsignados->links() }}
                                      </div>
                                  @else
                                      <h2 class="text-center font-bold mt-5">
                                          No hay proyectos asignados.
                                      </h2>
                                  @endif
                              </div>
                          </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  @if (session('error'))
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
          Swal.fire({
              position: 'center',
              icon: 'error',
              text: '{{ session('error') }}',
              confirmButtonText: 'Aceptar',
              confirmButtonColor: '#62836C',
              showConfirmButton: true,
              //timer: 2500
          })
      </script>
  @endif
  @if (session('success'))
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
          Swal.fire({
              position: 'center',
              icon: 'success',
              text: '{{ session('success') }}',
              confirmButtonColor: '#62836C',
              showConfirmButton: false,
              timer: 1500
          })
      </script>
  @endif
</div>
