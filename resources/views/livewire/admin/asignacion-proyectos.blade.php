<div x-data>
   
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignacion de proyectos') }}
        </h2>  
    </x-slot>
  
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <div x-data="{ activeTab: {{ $activeTab }} }">
                  <div class="border-b border-gray-200">
                      <nav class="-mb-px flex">
                          <button @click="activeTab = 1" :class="{'border-verde': activeTab === 1}"  class="w-1/2 py-4 px-6 bg-white rounded-t-lg font-semibold text-gray-700 border-b-2 border-transparent hover:border-verde focus:border-verde">
                              Asignar proyectos
                          </button>
                          <button @click="activeTab = 2" :class="{'border-verde': activeTab === 2}"  class="w-1/2 py-4 px-6 bg-white rounded-t-lg font-semibold text-gray-700 border-b-2 border-transparent hover:border-verde focus:border-verde">
                              Proyectos asignados
                          </button>
                      </nav>
                  </div>
              
                      <div x-show="activeTab === 1" class="py-6 px-4">
                          <!-- Contenido de la Sección 1 -->
                          <form wire:submit.prevent="save">
                            @csrf
                            <div>
                              @if(auth()->user()->rol === 2)
                              <div class="my-6">
                                <h3>Filtros:</h3>
                                <label for="idConvocatoria">
                                  Convocatoria:
                                </label>
                                <select class="sm:w-auto w-full"  id="idConvocatoria" name="idConvocatoria" wire:model="idConvocatoria"  >
                                  <option value="0">Todas</option>
                                  @foreach ($convocatorias as $convocatoria)
                                  <option value="{{ $convocatoria['CveEntCnv'] }}" >{{ $convocatoria['Tipo_Proyecto']}} - {{ $convocatoria['NomEntCnv']}}</option>
                                  @endforeach
                                </select>
                              </div>
                              @endif
                              @if(auth()->user()->rol === 1)
                              <div class="my-6">
                                <h3>Filtros:</h3>
                                <label for="idTipoProyecto">
                                  Tipo proyecto:
                                </label>
                                <select class="sm:w-auto w-full"  id="idTipoProyecto" name="idTipoProyecto" wire:model="idTipoProyecto"  >
                                  <option value="0">Todos</option>
                                  @foreach ($tipoProyectos as $tipo)
                                  <option value="{{ $tipo['Tipo_Proyecto'] }}" >{{ $tipo['Tipo_Proyecto'] }}</option>
                                  @endforeach
                                </select>
                              </div>
                              @endif

                              <div class="my-6">
                                <label for="idEspacioAcademico">
                                  Espacio académico:
                                </label>
                                <select class="sm:w-auto w-full" id="idEspacioAcademico" name="idEspacioAcademico"  wire:model="idEspacioAcademico" >
                                  <option value="0">Todos</option>
                                  @foreach ($espaciosAcademicos as $espacioAcademico)
                                  <option value="{{ $espacioAcademico['CveCenCos'] }}" >{{ $espacioAcademico['CveCenCos']}} - {{ $espacioAcademico['NomCenCos']}}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="my-6">
                                
                                  <input type="text" wire:model="search"
                                                      class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-auto"
                                                      placeholder="Buscar por clave, tipo...">
                              </div>
                              <div class="my-6">
                                <label>
                                  Proyectos disponibles para asignar
                                @error('proyectosSeleccionados') <span class=" text-rojo">{{ $message }}</span> @enderror
                                </label>
                              </div>
                              <div   class="overflow-x-auto">
                                <button type="button" wire:click="resetearSeleccionados()" class=" btn-warning sm:w-auto w-5/6">Resetear seleccion</button>

                                <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                                    <thead>
                                        <tr class="bg-blanco">
                                            <th class="w-[12%]">Clave del proyecto</th>
                                            <th class="w-[18%]">Nombre</th>
                                            <th class="w-[15%]">Espacio académico</th>
                                            <th class="w-[15%]">Convocatoria</th>
                                            <th class="w-[15%]">Tipo proyecto</th>
                                            <th class="w-[8%]">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($proyectosSinAsignar as $proyecto)
                                            <tr class="border-b-gray-200 border-transparent">
                                                <td> {{ $proyecto->CveEntPry }} </td>
                                                <td>{{ $proyecto->NomEntPry }}</td>
                                                <td> {{ $proyecto->NomCenCos }}</td>
                                                <td> {{ $proyecto->NomEntCnv }}</td>
                                                <td> {{ $proyecto->Tipo_Proyecto }}</td>
                                                <th class="w-[148px]">
                                                  <label>
                                                    <input type="checkbox"  value="{{ $proyecto->CveEntPry }}" wire:click="toggleProyectoSeleccionado('{{ $proyecto->CveEntPry }}')" {{ in_array($proyecto->CveEntPry, $proyectosSeleccionados) ? 'checked' : '' }} >
                                                  </label>
                                          
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                           
                                {{$proyectosSinAsignar->links()}}
                            </div>
                            <div class="my-6">
                              <label for="idRevisor">
                                Seleccione el revisor:
                              </label>
                              <select class="sm:w-auto w-full"  id="idRevisor" name="idRevisor" wire:model="idRevisor" >
                                <option value="0">Selecciona una opción</option>
                                @foreach ($revisores as $revisor)
                                <option value="{{ $revisor['id'] }}" >{{ $revisor['name']}} {{ $revisor['apePaterno']}} {{ $revisor['apeMaterno']}}</option>
                                @endforeach
                              </select>
                              @error('idRevisor') <span class=" text-rojo">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" @click="saveConfirmation()" class="btn-success sm:w-auto w-5/6">Guardar</button>
                            </div>
                          </form>
                      </div>
                      <div x-show="activeTab === 2" class="py-6 px-4">
                          <!-- Contenido de la Sección 2 -->
                          <div>
                            <div class="my-6">
                              <h3>Filtros:</h3>
                            {{--   <label for="idRevisorAsignados">
                                Revisor:
                              </label>
                              <select class="sm:w-auto w-full"  id="idRevisorAsignados" name="idRevisorAsignados" wire:model="idRevisorAsignados" >
                                <option value="0">Todos</option>
                                @foreach ($revisores as $revisor)
                                <option value="{{ $revisor['id'] }}" >{{ $revisor['name']}} {{ $revisor['apePaterno']}} {{ $revisor['apeMaterno']}}</option>
                                @endforeach
                              </select>
                              @error('idRevisorAsignados') <span class=" text-rojo">{{ $message }}</span> @enderror --}}
                            </div>
                            @if(auth()->user()->rol === 2)
                            <div class="my-6">
                              <label for="idConvocatoriaAsignados">
                                Convocatoria:
                              </label>
                              <select class="sm:w-auto w-full"  id="idConvocatoriaAsignados" name="idConvocatoriaAsignados" wire:model="idConvocatoriaAsignados"  >
                                <option value="0">Todas</option>
                                @foreach ($convocatorias as $convocatoria)
                                <option value="{{ $convocatoria['CveEntCnv'] }}" >{{ $convocatoria['Tipo_Proyecto']}} - {{ $convocatoria['NomEntCnv']}}</option>
                                @endforeach
                              </select>
                            </div>
                            @endif
                            @if(auth()->user()->rol === 1)
                            <div class="my-6">
                              <label for="idTipoProyectoAsignados">
                                Tipo de proyecto:
                              </label>
                              <select class="sm:w-auto w-full"  id="idTipoProyectoAsignados" name="idTipoProyectoAsignados" wire:model="idTipoProyectoAsignados"  >
                                <option value="0">Todos</option>
                                @foreach ($tipoProyectos as $tipo)
                                <option value="{{ $tipo['Tipo_Proyecto'] }}" >{{ $tipo['Tipo_Proyecto'] }}</option>
                                @endforeach
                              </select>
                            </div>
                            @endif
                            <div class="my-6">
                              <label for="idEspacioAcademicoAsignados">
                                Espacio académico:
                              </label>
                              <select class="sm:w-auto w-full" id="idEspacioAcademicoAsignados" name="idEspacioAcademicoAsignados"  wire:model="idEspacioAcademicoAsignados" >
                                <option value="0">Todos</option>
                                @foreach ($espaciosAcademicos as $espacioAcademico)
                                <option value="{{ $espacioAcademico['CveCenCos'] }}" >{{ $espacioAcademico['CveCenCos']}} - {{ $espacioAcademico['NomCenCos']}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="my-6">
                              
                                <input type="text" wire:model="searchAsignados"
                                                    class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-auto"
                                                    placeholder="Buscar por clave, tipo...">
                  
                            </div>
                            <div class="my-6">
                              <label for="id_rubro">
                                Proyectos asignados
                              </label>
                            </div>
                            <div class="overflow-x-auto">
                              @if($proyectosAsignados->first())
                              <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                                  <thead>
                                      <tr class="bg-blanco">
                                          <th class="w-[12%]">Clave del proyecto</th>
                                          <th class="w-[18%]">Nombre</th>
                                          <th class="w-[18%]">Tipo</th>
                                          <th class="w-[18%]">Espacio académico</th>
                                          <th class="w-[15%]">Revisor asignado</th>
                                          <th class="w-[8%]">Acciones</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($proyectosAsignados as $proyecto)
                                          <tr class="border-b-gray-200 border-transparent">
                                              <td> {{ $proyecto->CveEntPry }} </td>
                                              <td>{{ $proyecto->NomEntPry }}</td>
                                              <td>{{ $proyecto->Tipo_Proyecto }}</td>
                                              <td> {{ $proyecto->NomCenCos }}</td>
                                              @if($proyecto->id_revisor)
                                              <td>{{ $proyecto->nombre_revisor }}</td>
                                              @endif
                                              <th class="w-[148px]">
                                                <button type="button"  x-on:click="$wire.emit('openModal', 'admin.reasignar-proyecto-modal', { 'id_proyecto': {{ $proyecto->CveEntPry }}, 'id_revisor': {{ $proyecto->id_revisor }}})" class="btn-success sm:w-auto w-5/6">
                                                 Reasignar
                                                </button>
                                        
                                              </th>
                                          </tr>
                                      @endforeach
                                  </tbody>
                              </table>
                   
                              {{$proyectosAsignados->links()}}
                             
                         @else
                             No hay proyectos asignados
                         @endif
                          </div>
                          
                          </div>
                      </div>
              </div>
              </div>
                </div>
            </div>
        </div>
        @if(session('error'))
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
          Swal.fire({
            position: 'top-center',
            icon: 'error',
            text: '{{ session('error') }}',
            confirmButtonText: 'Aceptar!',
            confirmButtonColor: '#62836C',
            showConfirmButton: true,
            //timer: 2500
          })
        </script>
        @endif
        @if(session('success-asignacion'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
        Swal.fire({
          position: 'top-center',
          icon: 'success',
          text: '{{ session('success') }}',
          confirmButtonColor: '#62836C',
          showConfirmButton: false,
          timer: 1000
        })
      </script>
      @endif
 </div>

 