<div x-data>
  <x-slot name="header">
      <h2 class="font-semibold text-xl leading-tight">
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
                                      <button type="button" class="bg-gray-400 sm:w-auto w-full sm:mt-0 mt-4"
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
                                                  <th class="w-[13%] cursor-pointer"
                                                      wire:click="sortProyAsignados('clave_uaem')">
                                                      Clave del proyecto
                                                      <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                  </th>
                                                  <th class="w-[32%] cursor-pointer"
                                                      wire:click="sortProyAsignados('nombre_proyecto')">
                                                      Nombre
                                                      <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                  </th>
                                                  <th class="w-[15%] cursor-pointer"
                                                      wire:click="sortProyAsignados('espacio_academico')">
                                                      Espacio académico
                                                      <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                  </th>
                                                  <th class="w-[15%] cursor-pointer"
                                                      wire:click="sortProyAsignados('tipo_proyecto')">
                                                      Tipo proyecto
                                                      <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                  </th>
                                                  <th class="w-[15%] cursor-pointer"
                                                      wire:click="sortProyAsignados('tipo_proyecto')">
                                                      Fecha inicio
                                                      <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                  </th>
                                                  <th class="w-[15%] cursor-pointer"
                                                      wire:click="sortProyAsignados('tipo_proyecto')">
                                                      Fecha fin
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
                                                      <td> {{ $proyecto->tipo_proyecto }}</td>
                                                      <td> {{ $proyecto->fecha_inicio }}</td>
                                                      <td> {{ $proyecto->fecha_final }}</td>

                                                      <th class="w-[148px]">
                                                          <button type="button"
                                                              x-on:click="$wire.emit('openModal', 'revisores.editar-fechas-proyecto-modal', { 'id_proyecto': {{ $proyecto->id_proyecto }}, 'clave_uaem': '{{ $proyecto->clave_uaem }}', 'clave_digcyn': '{{ $proyecto->clave_digcyn }}'})"
                                                              class="btn-success">
                                                              Editar
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
              position: 'top-center',
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
              position: 'top-center',
              icon: 'success',
              text: '{{ session('success') }}',
              confirmButtonColor: '#62836C',
              showConfirmButton: false,
              timer: 1500
          })
      </script>
  @endif
</div>
