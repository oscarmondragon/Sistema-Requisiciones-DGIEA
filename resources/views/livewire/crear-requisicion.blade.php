<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div >
                    <h3>Seleccione el tipo de requerimiento</h3>
                        <div>
                            <select id="tipo" wire:model="tipo" class="sm:w-auto w-full mt-2">
                                <option value="0">Selecciona un opción</option>
                                @foreach ($tiposRequisicion as $tipoRequisicion)
                                    @if (Session::get('iniciar_captura') == 0)
                                        <option @class('text-gray-400') value="{{ $tipoRequisicion->id }}" disabled>
                                            {{ $tipoRequisicion->descripcion }}</option>
                                    @else
                                        @if (Session::get('tiempo_restante_solicitudes') == '')
                                            <option value="{{ $tipoRequisicion->id }}">
                                                {{ $tipoRequisicion->descripcion }}
                                            </option>
                                        @elseif(
                                            (Session::get('tiempo_restante_solicitudes') < 0 and $tipoRequisicion->id == 2) ||
                                                (Session::get('tiempo_restante_adquisiciones') < 0 and $tipoRequisicion->id == 1))
                                            <option @class('text-gray-400') value="{{ $tipoRequisicion->id }}"
                                                disabled>
                                                {{ $tipoRequisicion->descripcion }}</option>
                                        @else
                                            <option value="{{ $tipoRequisicion->id }}">
                                                {{ $tipoRequisicion->descripcion }}
                                            </option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                        </div>
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

                @if ($tipo == 1)
                    @php
                        $route = route('cvu.create-adquisiciones');
                    @endphp
                @elseif ($tipo == 2)
                    @php
                        $route = route('cvu.create-solicitudes');
                    @endphp
                @endif

                @if (!empty($route))
                    @php
                        return redirect($route);
                    @endphp
                @endif
            </div>
        </div>
    </div>
</div>
