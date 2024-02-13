<div x-data class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-textos_generales">
                <div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="flex items-center">
                            <img src="img/iconos/ic_req_pendientes.png" alt="Requerimientos pendientes">
                            <h3 class="text-xl pl-2">Requerimientos pendientes de enviar</h3>
                        </div>
                        @if (session('success'))
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                            <script>
                                Swal.fire({
                                    position: 'center',
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
                                <div class="flex flex-wrap items-end gap-x-2 sm:-mt-7">
                                    <div class="sm:w-2/3  w-full">
                                        <select class="sm:w-auto  w-full" id="categoria" name="categoria"
                                            wire:model="categoria">
                                            <option value="0">Todo</option>
                                            @foreach ($tipoRequisicion as $tipo)
                                                <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                                            @endforeach
                                            <option value="3">Creada</option>
                                            <option value="4">Rechazado VoBo</option>
                                        </select>
                                        <input type="text" wire:model="search" onfocus="this.value=null"
                                            class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-full"
                                            placeholder="Buscar por clave, tipo...">

                                    </div>


                                    <div class="flex-1 md:mt-0 mt-2">
                                        <p class="text-verde font-semibold">Filtrar por fecha</p>
                                        <input type="date" name="f_inicial" id="f_inicial" wire:model="f_inicial"
                                            class="inputs-formulario-solicitudes sm:w-[160px] w-full">
                                        <input type="date" name="f_final" id="f_final" wire:model="f_final"
                                            class="inputs-formulario-solicitudes sm:ml-4 md:mt-0 mt-2 sm:w-[160px] w-full">
                                    </div>
                                </div>
                                <div class="text-end mt-3">
                                    <button class="bg-blue-600" wire:click="limpiarFiltros">Limpiar filtros</button>
                                </div>

                                @if ($adquisiciones->first())
                                    <div class="overflow-x-auto">
                                        <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto mt-4">
                                            <thead>
                                                <tr class="bg-blanco">
                                                    <th class="w-[15%] cursor-pointer"
                                                        wire:click="sort('id_requerimiento')">
                                                        Clave requerimiento
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[28%] cursor-pointer"
                                                        wire:click="sort('nombre_cuenta')">
                                                        Rubro
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[17%] cursor-pointer" wire:click="sort('descripcion')">
                                                        Tipo requerimiento
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[13%] sm:text-center cursor-pointer"
                                                        wire:click="sort('estado')">
                                                        Estatus
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[15%] cursor-pointer"
                                                        wire:click="sort('modificacion')">
                                                        Ultima modificación
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[12%]">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($adquisiciones as $adquisicion => $valor)
                                                    <tr class="border-b-gray-200 border-transparent">
                                                        <td> {{ $valor->id_requerimiento }} </td>
                                                        <td> {{ $valor->nombre_cuenta }} </td>
                                                        <td> {{ $valor->descripcion }} </td>
                                                        <td class="sm:text-center">
                                                            @if ($valor->estatus_color == 'AMARILLO')
                                                                <span
                                                                    class="bg-yellow-200 text-yellow-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-yellow-300">
                                                                    {{ $valor->estado }}
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="bg-red-200 text-red-700 rounded-full p-1 px-2 text-xs font-bold text-center block mx-1 border border-red-300">
                                                                    {{ $valor->estado }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td> {{ $valor->modificacion }}</td>
                                                        @if ($valor->tipo_requerimiento == 1)
                                                            <td>
                                                                <a href="{{ route('adquisiciones.editar', $valor->id) }}">
                                                                    <button class="btn-tablas" title="Editar">
                                                                        <img src="{{ asset('img/botones/btn_editar.png') }}"
                                                                            alt="Botón Editar">
                                                                    </button>
                                                                </a>
                                                                <button type="button"
                                                                    @click="deleteConfirmation('{{ $valor->id }}', '{{$valor->id_requerimiento}}')"
                                                                    class="btn-tablas" title="Eliminar">
                                                                    <img src="{{ 'img/botones/btn_eliminar.png' }}"
                                                                        alt="Botón Eliminar">
                                                                </button>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <a href="{{ route('solicitudes.editar', $valor->id) }}"
                                                                    title="Editar">
                                                                    <button class="btn-tablas" title="Editar">
                                                                        <img src="{{ 'img/botones/btn_editar.png' }}"
                                                                            alt="Botón Editar">
                                                                    </button>
                                                                </a>
                                                                <button type="button"
                                                                    @click="deleteSolicitud('{{ $valor->id }}', '{{$valor->id_requerimiento}}')"
                                                                    class="btn-tablas" title="Eliminar">
                                                                    <img src="{{ 'img/botones/btn_eliminar.png' }}"
                                                                        alt="Image/png">
                                                                </button>
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
                                    {{ $adquisiciones->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="flex items-center mt-5">
                            <img src="img/iconos/ic_req_vobo.png" alt="Requerimientos pendientes de visto bueno">
                            <h3 class="text-xl pl-2">Requerimientos pendientes de visto bueno</h3>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 ">
                                <div class="flex flex-wrap items-end gap-2 sm:-mt-7">
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
                                        <input type="text" wire:model="searchVobo" onfocus="value=''"
                                            class="inputs-formulario-solicitudes md:mt-0 mt-2 p-2.5 sm:w-96 w-full"
                                            placeholder="Buscar por clave, tipo...">
                                    </div>

                                    <div class="flex-1 md:mt-0 mt-2">

                                        <p class="text-verde font-semibold">Filtrar por fecha</p>
                                        <input type="date" name="f_inicial_vobo" id="f_inicial_vobo"
                                            wire:model="f_inicial_vobo"
                                            class=" inputs-formulario-solicitudes sm:w-[160px] w-full">
                                        <input type="date" name="f_final_vobo" id="f_final_vobo"
                                            wire:model="f_final_vobo"
                                            class="inputs-formulario-solicitudes sm:ml-4 md:mt-0 mt-2 sm:w-[160px] w-full">
                                    </div>
                                </div>
                                <div class="text-end mt-3">
                                    <button class="bg-blue-600" wire:click="limpiarFiltrosVobo">Limpiar filtros</button>
                                </div>

                                @if ($adquisicionesVistosBuenos->first())
                                    <div class="overflow-x-auto">
                                        <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto mt-6">
                                            <thead>
                                                <tr class="bg-blanco">
                                                    <th class="w-[15%] cursor-pointer"
                                                        wire:click="sort('id_requerimiento')">
                                                        Clave requerimiento
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[17%] cursor-pointer"
                                                        wire:click="sort('nombre_cuenta')">
                                                        Rubro
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[14%] cursor-pointer"
                                                        wire:click="sort('descripcion')">
                                                        Tipo requerimiento
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[9%] cursor-pointer" wire:click="sort('vobo_rt')">
                                                        VoBo RT
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[9%] cursor-pointer" wire:click="sort('vobo_admin')">
                                                        VoBo <span class="pl-1 text-verde font-bold">&#8645;</span> Administrativo
                                                    </th>
                                                    <th class="w-[14%] text-center cursor-pointer"
                                                        wire:click="sort('estado')">
                                                        Estatus
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[15%] cursor-pointer"
                                                        wire:click="sort('modificacion')">
                                                        Ultima modificación
                                                        <span class="pl-1 text-verde font-bold">&#8645;</span>
                                                    </th>
                                                    <th class="w-[7%]">
                                                        Acciones
                                                    </th>
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
                                                                class="bg-gray-200 text-gray-700 rounded-full p-1 px-2 font-bold text-center block mx-1 border border-gray-300">
                                                                {{ $valorvobo->estado }}
                                                            </span>
                                                        </td>
                                                        <td> {{ $valorvobo->modificacion }}</td>
                                                        @if ($valorvobo->tipo_requerimiento_v == 1)
                                                            <th class="w-[148px] text-center">
                                                                @if (Session::get('id_user') != $valorvobo->id_emisor)
                                                                    <a href="{{ route('adquisicion.vobo', $valorvobo->id) }}">
                                                                        <button class="btn-tablas" title="Dar visto bueno">
                                                                            <img src="{{ 'img/botones/btn_vobo.png' }}" alt="VoBo">
                                                                        </button>
                                                                    </a>
                                                                @endif
                                                            </th>
                                                        @else
                                                            <th class="w-[148px] text-center">
                                                                @if (Session::get('id_user') != $valorvobo->id_emisor)
                                                                    <a href="{{ route('solicitud.vobo', $valorvobo->id) }}">
                                                                        <button class="btn-tablas" title="Dar visto bueno">
                                                                            <img src="{{ 'img/botones/btn_vobo.png' }}" alt="VoBo">
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
                                @else
                                    <h2 class="text-center font-bold mt-5">No hay requerimientos pendientes de visto
                                        bueno.</h2>
                                @endif
                                <div class="mt-4">
                                    {{ $adquisicionesVistosBuenos->links() }}
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
                function deleteConfirmation(adquisicionId, clave_adquisicion) {
                    // Now you can access the event object (e) directly
                    Swal.fire({
                        title: '¿Estás seguro de eliminarlo?',
                        text: 'Un requerimiento eliminado no se puede recuperar.',
                        position: 'center',
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
                                position: 'center',
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#62836C',
                                text: 'Se eliminó correctamente el requerimiento con clave ' + clave_adquisicion + '.',
                                showConfirmButton: false,
                                timer: 2500
                            })
                        }
                    });
                }

                function deleteSolicitud(solicitudId, claveSolicitud) {
                    // Now you can access the event object (e) directly
                    Swal.fire({
                        title: '¿Estás seguro de eliminarlo?',
                        text: 'Un requerimiento eliminado no se puede recuperar.',
                        position: 'center',
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
                                position: 'center',
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#62836C',
                                text: 'Se eliminó correctamente el requerimiento con clave ' + claveSolicitud + '.',
                                showConfirmButton: false,
                                timer: 2000
                            })
                        }
                    });
                }
            </script>
        @endpush
    </div>
