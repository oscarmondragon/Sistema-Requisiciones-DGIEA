<div x-data>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Asignación de proyectos') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div x-data="{ activeTab: {{ $activeTab }} }">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex">
                                <button @click="activeTab = 1" :class="{ 'border-verde': activeTab === 1 }"
                                    class="w-1/2 py-4 px-6 bg-white rounded-none font-semibold text-gray-700 border-b-2 border-transparent hover:border-verde focus:border-verde">
                                    Asignar proyectos
                                </button>
                                <button @click="activeTab = 2" :class="{ 'border-verde': activeTab === 2 }"
                                    class="w-1/2 py-4 px-6 bg-white rounded-none font-semibold text-gray-700 border-b-2 border-transparent hover:border-verde focus:border-verde">
                                    Proyectos asignados
                                </button>
                            </nav>
                        </div>

                        <h3 class="mt-5 ml-4">Filtros:</h3>


                        <div x-show="activeTab === 1" class="pt-6 px-4">
                            <!-- Contenido de la Sección 1 -->
                            <form wire:submit.prevent="save">
                                @csrf
                                <div>
                                    @if (auth()->user()->rol === 2)
                                        <div class="mb-6">
                                            {{-- <h3>Filtros:</h3> --}}
                                            <label for="idConvocatoria" class="block">Convocatoria:</label>
                                            <select class="sm:w-3/4 w-full" id="idConvocatoria" name="idConvocatoria"
                                                wire:model="idConvocatoria">
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

                                    <div class="sm:grid sm:grid-cols-2 sm:w-full gap-x-8">
                                        @if (auth()->user()->rol === 1)
                                            <div class="flex-col">
                                                <label for="idTipoProyecto" class="sm:inline-block block">Tipo de proyecto:</label>
                                                <select class="w-full mt-1" id="idTipoProyecto"
                                                    name="idTipoProyecto" wire:model="idTipoProyecto">
                                                    <option value="0">Todos</option>
                                                    @foreach ($tipoProyectos as $tipo)
                                                        <option value="{{ $tipo['Tipo_Proyecto'] }}">
                                                            {{ $tipo['Tipo_Proyecto'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="flex-col sm:mt-0 mt-2">
                                            <label for="idEspacioAcademico">
                                                Espacio académico:
                                            </label>
                                            <select class="w-full mt-1" id="idEspacioAcademico"
                                                name="idEspacioAcademico" wire:model="idEspacioAcademico">
                                                <option value="0">Todos</option>
                                                @foreach ($espaciosAcademicos as $espacioAcademico)
                                                    <option value="{{ $espacioAcademico['CveCenCos'] }}">
                                                        {{ $espacioAcademico['CveCenCos'] }} -
                                                        {{ $espacioAcademico['NomCenCos'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                    <div class="sm:grid sm:grid-cols-2 items-center my-6">
                                        <div class="flex-col">
                                            <label for="">Buscar:</label>
                                            <input type="text" wire:model="search"
                                                class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-3/4 w-full inline-block"
                                                placeholder="Buscar por clave del proyecto, nombre...">
                                        </div>

                                        <div class="flex-col">
                                            <button type="button" class="bg-blue-600 sm:w-auto w-full sm:mt-0 mt-4"
                                                wire:click="limpiarFiltros">
                                                Limpiar filtros
                                            </button>
                                        </div>
                                    </div>

                                    <div class="sm:grid sm:grid-cols-2 items-end">
                                        <div>
                                            <h3>Proyectos disponibles para asignar</h3>
                                            @error('proyectosSeleccionados')
                                                    <span class="text-rojo">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <div class="sm:flex sm:justify-end">
                                                <button type="button" wire:click="resetearSeleccionados()"
                                                    class="hidden sm:flex btn-warning sm:w-auto w-5/6">Resetear</button>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($proyectosSinAsignar->first())
                                        <div class="overflow-x-auto mt-4">
                                            <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto">
                                                <thead>
                                                    <tr class="bg-blanco">
                                                        <th class="w-[13%] cursor-pointer"
                                                            wire:click="sort('CvePryUaem')">
                                                            Clave del proyecto
                                                            <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                        </th>
                                                        <th class="w-[39%] cursor-pointer"
                                                            wire:click="sort('NomEntPry')">
                                                            Nombre
                                                            <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                        </th>
                                                        <th class="w-[17%] cursor-pointer"
                                                            wire:click="sort('NomCenCos')">
                                                            Espacio académico
                                                            <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                        </th>
                                                        <th class="w-[15%] cursor-pointer"
                                                            wire:click="sort('NomEntCnv')">
                                                            Convocatoria
                                                            <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                        </th>
                                                        <th class="w-[10%] cursor-pointer"
                                                            wire:click="sort('Tipo_Proyecto')">
                                                            Tipo proyecto
                                                            <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                        </th>
                                                        <th class="w-[7%]">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($proyectosSinAsignar as $proyecto)
                                                        <tr class="border-b-gray-200 border-transparent">
                                                            {{-- <td> {{ $proyecto->CveEntPry }}  </td> --}}
                                                            <td> 
                                                                {{ $proyecto->Clave_DIGCYN == null ? $proyecto->CvePryUaem : $proyecto->Clave_DIGCYN }}
                                                            </td>
                                                            <td>{{ $proyecto->NomEntPry }}</td>
                                                            <td> {{ $proyecto->NomCenCos }}</td>
                                                            <td> {{ $proyecto->NomEntCnv }}</td>
                                                            <td> {{ $proyecto->Tipo_Proyecto }}</td>
                                                            <th class="w-[148px] text-center">
                                                                <label>
                                                                    <input type="checkbox" class="cursor-pointer"
                                                                        value="{{ $proyecto->CveEntPry }}"
                                                                        wire:click="toggleProyectoSeleccionado('{{ $proyecto->CveEntPry }}')"
                                                                        {{ in_array($proyecto->CveEntPry, $proyectosSeleccionados) ? 'checked' : '' }}>
                                                                </label>
                                                            </th>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <div class="mt-5">
                                                {{ $proyectosSinAsignar->links() }}
                                            </div>
                                        </div>
                                    @else
                                        <h2 class="text-center font-bold mt-5">No hay proyectos disponibles para
                                            asignar.</h2>
                                    @endif
                                    <div class="mt-14">
                                        <label for="idRevisor">
                                            Seleccione el revisor:
                                        </label>
                                        <select class="sm:w-1/2 w-full" id="idRevisor" name="idRevisor"
                                            wire:model="idRevisor">
                                            <option value="0">Selecciona una opción</option>
                                            @foreach ($revisores as $revisor)
                                                <option value="{{ $revisor['id'] }}">{{ $revisor['name'] }}
                                                    {{ $revisor['apePaterno'] }} {{ $revisor['apeMaterno'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('idRevisor')
                                            <span class=" text-rojo">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-8 sm:text-end text-center">
                                        <button type="submit" @click="saveConfirmation()"
                                            class="btn-success sm:w-auto w-5/6">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div x-show="activeTab === 2" class="py-6 px-4">
                            <!-- Contenido de la Sección 2 -->
                            <div>
                                @if (auth()->user()->rol === 2)
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
                                    @if (auth()->user()->rol === 1)
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
                                    <div class="flex-col">
                                        <label for="idRevisorAsignados">Revisor:</label>
                                        <select class="sm:w-[310px] w-full" id="idRevisorAsignados"
                                            name="idRevisorAsignados" wire:model="idRevisorAsignados">
                                            <option value="0">Todos</option>
                                            @foreach ($revisores as $revisor)
                                                <option value="{{ $revisor['id'] }}">
                                                    {{ $revisor['name'] }} {{ $revisor['apePaterno'] }}
                                                    {{ $revisor['apeMaterno'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('idRevisorAsignados')
                                            <span class=" text-rojo">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="flex-col sm:mt-0 mt-4">
                                        <label for="buscar">Buscar:</label>
                                        <input type="text" id="buscar" wire:model="searchAsignados"
                                            class="inputs-formulario-solicitudes sm:w-[315px] w-full"
                                            placeholder="Buscar por clave del proyecto, nombre...">
                                    </div>

                                    <div class="flex-col sm:ml-20">
                                        <button type="button" class="bg-blue-600 sm:w-auto w-full sm:mt-0 mt-4"
                                            wire:click="limpiarFiltrosProyAsignados">
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
                                                        wire:click="sortProyAsignados('id_revisor')">
                                                        Revisor asignado
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
                                                        @if ($proyecto->id_revisor)
                                                            <td class="capitalize">
                                                                {{ $proyecto->nameUser->name }}
                                                                {{ $proyecto->nameUser->apePaterno }}
                                                                {{ $proyecto->nameUser->apeMaterno }}
                                                            </td>
                                                        @endif
                                                        <th class="w-[148px]">
                                                            <button type="button"
                                                                x-on:click="$wire.emit('openModal', 'admin.reasignar-proyecto-modal', { 'id_proyecto': {{ $proyecto->id_proyecto }}, 'id_revisor': {{ $proyecto->id_revisor }}, 'clave_uaem': '{{ $proyecto->clave_uaem }}', 'clave_digcyn': '{{ $proyecto->clave_digcyn }}'})"
                                                                class="btn-success">
                                                                Reasignar
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
    @if (session('success-asignacion'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                position: 'top-center',
                icon: 'success',
                text: '{{ session('success-asignacion') }}',
                confirmButtonColor: '#62836C',
                showConfirmButton: false,
                timer: 1500
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
