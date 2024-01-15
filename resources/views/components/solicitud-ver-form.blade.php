@if ($solicitud->observaciones_vobo || $solicitud->observaciones)
<div class="my-4">
    <p class="bg-red-100 text-red-500 font-bold py-1 px-2 rounded-sm border border-red-500">
        <svg class="inline-block w-5 h-5 me-3" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        @if ($solicitud->observaciones_vobo)
            Observaciones de rechazo:
        <span class="block pl-12 font-normal"><span class="font-bold">Por visto bueno:</span> {{ $solicitud->observaciones_vobo }}</span>
        @endif
        @if ($solicitud->observaciones)
        <span class="block pl-12 font-normal"><span class="font-bold">Por DGIEA: </span> {{ $solicitud->observaciones }}</span>
        @endif
    </p>
</div>
@endif
<div>
    <?php
    
    use Carbon\Carbon;
    ?>
                                <div class="mt-6">
                                    <label for="id_rubro">
                                        Rubro:
                                    </label>
                                    <select class="sm:w-auto w-full" title="Este campo no se puede modificar."
                                        id="id_rubro" name="id_rubro" wire:model="id_rubro" disabled>
                                        @foreach ($cuentasContables as $cuentaContable)
                                            <option value="{{ $cuentaContable->id }}"
                                                data-id-especial="{{ $cuentaContable->id_especial }}">
                                                {{ $cuentaContable->nombre_cuenta }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-8" x-data x-init="tipoComprobacionOption = '{{ $tipo_comprobacion }}'">
                                    <label for="monto_total">Monto a solicitar: </label>
                                    <input type="number" id="monto_total" name="monto_total" wire:model="monto_total"
                                        class="inputs-formulario-solicitudes w-40"
                                        title="Este campo no se puede modificar." min="0" placeholder="$ 0000.00" disabled>

                                </div>

                                <div class="mt-8">
                                    <label for="nombre_expedido">Expedido a nombre de: </label>
                                    <input type="text" readonly id="nombre_expedido" wire:model="nombre_expedido"
                                        class="inputs-formulario-solicitudes sm:w-96 w-full cursor-not-allowed"
                                        placeholder="Nombre" title="Este campo no se puede modificar." disabled>
                                </div>

                                <div class="mt-8">
                                    <label for="concepto"> Concepto</label>
                                    <input wire:model="concepto"
                                        class="inputs-formulario-solicitudes sm:w-[477px] w-full"
                                        id="concepto" type="text" placeholder="Concepto" title="Este campo no se puede modificar."
                                        disabled>
                                </div>

                                <div class="mt-8">
                                    <label for="justificacionS">Justificación</label>
                                    <textarea wire:model="justificacionS" class="sm:w-3/4 w-full block" rows="3" cols="30"
                                        id="justificacionS" placeholder="Justificación" title="Este campo no se puede modificar." disabled></textarea>
                                </div>

                                @if ($id_rubro_especial == '2')
                                    <div class="mt-8">
                                        <label>Periodo</label>
                                        <div
                                            class="mt-2 sm:ml-10 sm:grid sm:grid-cols-2 gap-4 flex-col sm:w-3/4 w-full">
                                            <div class="flex-col">
                                                <label class="block mb-1" for="finicial">Fecha inicial:</label>
                                                <input wire:model="finicial"
                                                    class="inputs-formulario"
                                                    title="Este campo no se puede modificar." id="finicial" type="date"
                                                    placeholder=""
                                                    min="{{ Carbon::now()->addDay(15)->format('Y-m-d') }}" disabled>
                                            </div>
                                            <div class="flex-col">
                                                <label class="block mb-1 sm:mt-0 mt-4" for="ffinal">Fecha
                                                    final:</label>
                                                <input wire:model="ffinal" class="inputs-formulario"
                                                    title="Este campo no se puede modificar." id="ffinal" type="date"
                                                    placeholder=""
                                                    min="{{ Carbon::now()->addDay(15)->format('Y-m-d') }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($id_rubro_especial == '3')
                                    <div class="my-5">
                                        <label for="tipo_comprobacion">Tipo de solicitud</label>
                                        <div class="sm:ml-10 mt-2">
                                            <label class=" items-center">
                                                <input type="radio" x-model="tipoComprobacionOption"
                                                    wire:model='tipo_comprobacion' name="tipo_comprobacion"
                                                    value="vale"
                                                    title="Este campo no se puede modificar." disabled>
                                                <span class="ml-2">Vales</span>
                                            </label>
                                            <label class=" items-center ml-6">
                                                <input type="radio" x-model="tipoComprobacionOption"
                                                    wire:model='tipo_comprobacion' name="tipo_comprobacion"
                                                    value="ficha"
                                                    title="Este campo no se puede modificar." disabled>
                                                <span class="ml-2">Ficha de gasto</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <label for="bitacoraPdf">Bitacora firmada PDF</label>
                                        <br>
                                        <ul>
                                            @foreach ($docsbitacoraPdf as $index => $archivo)
                                                <li>
                                                    @if (isset($archivo['datos']['ruta_documento']))
                                                        <a href="#" class="text-dorado"
                                                            wire:click="descargarArchivo('{{ $archivo['datos']['ruta_documento'] }}', '{{ $archivo['datos']['nombre_documento'] }}')">
                                                            {{ $archivo['datos']['nombre_documento'] }}
                                                            <button type="button" class="btn-ver">Ver</button>
                                                        </a>
                                                    @else
                                                        {{ $archivo['datos']['nombre_documento'] }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>