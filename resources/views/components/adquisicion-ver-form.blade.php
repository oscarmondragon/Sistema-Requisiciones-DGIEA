@if ($adquisicion->observaciones_vobo || $adquisicion->observaciones || isset($queryObservaciones))
<div class="my-4">
    <p class="bg-red-100 text-red-500 font-bold py-1 px-2 rounded-sm border border-red-500">
        <svg class="inline-block w-5 h-5 me-3" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        Observaciones de rechazo:
        @if ($adquisicion->observaciones_vobo)
        <span class="block pl-12 font-normal"><span class="font-bold">Por visto bueno:</span> {{ $adquisicion->observaciones_vobo }}</span>
        @endif
        @if ($adquisicion->observaciones)
        <span class="block pl-12 font-normal"><span class="font-bold">Por DGIEA: </span> {{ $adquisicion->observaciones }}</span>
        @endif
        @if (isset($queryObservaciones))
        <span class="block pl-12 font-normal"><span class="font-bold">Por SIIA: </span> {{ $queryObservaciones }}</span>
        @endif
    </p>
</div>
@endif
<div>
    <div class="my-6">
        <label for="id_rubro">
        Rubro:
        </label>
        <select class="sm:w-auto w-full" required id="id_rubro" name="id_rubro" wire:model="id_rubro" title="Este campo no se puede modificar." disabled >
        @foreach ($cuentasContables as $cuentaContable)
        <option value="{{ $cuentaContable->id }}" data-id-especial="{{ $cuentaContable->id_especial }}">{{ $cuentaContable->nombre_cuenta }}</option>
        @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label>
        Descripción del bien o servicio:
        </label>
    </div>
    <div class="overflow-x-auto" wire:poll x-data="{ elementos: @entangle('bienes').defer, id_rubro: '{{ $id_rubro }}' }">
        <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto" >
        <thead>
            <tr class="bg-blanco">
            <th class="w-[26px]">#</th>
            <th class="w-[200px]">Descripción</th>
            <th class="w-[80px]">Cantidad</th>
            <th class="w-[80px]">Precio Unitario</th>
            <th class="w-[80px]">IVA</th>
            <th class="w-[80px]">Importe</th>
            @if ($id_rubro_especial == '1')
            <th class="w-[300px]">Justificación</th>
            <th class="w-[180px]">Beneficiados</th>
            @endif
            </tr>
        </thead>
        <tbody>
            <template x-for="(elemento, index) in elementos" :key="index">
            <tr class="border border-b-gray-200 border-transparent">
                <th class="w-[26px]" x-text="index + 1"> </th>
                {{-- :title="elemento.descripcion" --}}
                <th class="w-[200px]" :title="elemento.descripcion" x-text="elemento.descripcion.length > 85 ? elemento.descripcion.substring(0,85) + '...' : elemento.descripcion"> </th>
                <th class="w-[80px]" x-text="elemento.cantidad"> </th>
                <th class="w-[80px]" x-text="elemento.precio_unitario"> </th>
                <th class="w-[80px]" x-text="elemento.iva"> </th>
                <th class="w-[80px]" x-text="elemento.importe"> </th>
                @if ($id_rubro_especial == '1')
                <th class="w-[300px]" :title="elemento.justificacion_software" x-text="elemento.justificacion_software.length > 85 ? elemento.justificacion_software.substring(0,85) 
                                            + '...' : elemento.justificacion_software"></th>
                <th class="w-[180px]" x-html="'Alumnos: ' + elemento.alumnos +
                                        '<br>Profesores: ' + elemento.profesores_invest +
                                        '<br>Administrativos: ' + elemento.administrativos"> </th>
                @endif
            </tr>
            </template>
            <tr>
            @if ($id_rubro_especial == '1')
            <th></th>
            <th></th>
            @endif
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Subtotal</th>
            <th>$ {{$subtotal}}</th>
            </tr>
            <tr>
            @if ($id_rubro_especial == '1')
            <th></th>
            <th></th>
            @endif
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>IVA</th>
            <th>$ {{$iva}}</th>
            </tr>

            <tr class="border border-b-gray-200 border-transparent">
            @if ($id_rubro_especial == '1')
            <th></th>
            <th></th>
            @endif
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Total</th>
            <th>$ {{$total}}</th>
            </tr>
        </tbody>
        </table>

    <div class="my-5" x-data x-init="afectaSelectedOption = '{{ $afecta_investigacion }}'">
        <label for="afecta" class="text-dorado font-bold">
        ¿El cambio de alguna de las características del bien descritas en la cotización,
        afectan el desarrollo de la investigación?
        </label>
        <div class="mt-2">
        <label class="inline-flex items-center">
            <input type="radio" x-model="afectaSelectedOption" wire:model='afecta_investigacion' name="siAfecta" value="1"
            title="Este campo no se puede modificar." disabled>
            <span class="ml-2">Si</span>
        </label>
        <label class="inline-flex items-center ml-6">
            <input type="radio" x-model="afectaSelectedOption" wire:model='afecta_investigacion'  name="noAfecta" value="0"
            title="Este campo no se puede modificar." disabled>
            <span class="ml-2">No</span>
        </label>
        </div>

        <div x-show="afectaSelectedOption === '1'" class="flex flex-col">
        <label for="justificacion" class="my-2">Justificación académica:</label>
        <textarea value="{{ $adquisicion->justificacion_academica }}" id="justificacion" name="justificacion" 
            wire:model='justificacion_academica' placeholder="Justificación" title="Este campo no se puede modificar."
            class="sm:w-3/4 w-full" rows="2" cols="30" disabled>
        </textarea>
        </div>
    </div>

    <div class="mb-2" x-data x-init="exclusividadSelectedOption = '{{ $exclusividad }}'">
    <label for="afecta">
        ¿Es bien o servicio con exclusividad?
    </label>
    <div class="mt-2">
        <label class="inline-flex items-center">
        <input type="radio" x-model="exclusividadSelectedOption" wire:model='exclusividad' name="siExclusivo" value="1"
        title="Este campo no se puede modificar." disabled>
        <span class="ml-2">Si</span>
        </label>
        <label class="inline-flex items-center ml-6">
        <input type="radio" x-model="exclusividadSelectedOption" wire:model='exclusividad' wire:click="resetdocsCartaExclusividad" name="noExclusivo" value="0"
        title="Este campo no se puede modificar."  disabled>
        <span class="ml-2">No</span>
        </label>
    </div>

    <div x-show="exclusividadSelectedOption === '1'">
        <label for="cartaExlcusividad">Carta de exclusividad:</label>
        <br>
        <div wire:loading wire:target="docsCartaExclusividad">Cargando archivo...</div>
        <ul>
        @foreach($documentos as $index => $docCarta)
        @if($docCarta->tipoDocumento->id == 1)
        <li>
            @if(isset($docCarta->ruta_documento))
            <a href="#" class="text-dorado" wire:click="descargarArchivo('{{ $docCarta->ruta_documento }}', '{{ $docCarta->nombre_documento}}')">  {{ $docCarta->nombre_documento}}
            <button type="button" class="btn-ver">Ver</button>
            </a>
            @endif
        </li>
        @endif
        @endforeach
        </ul>
    </div>
    <div class="mt-2">
        <label for="cotizacionFirmada">Cotización PDF firmada:</label>
    
        <br>
        <div wire:loading wire:target="docsCotizacionesFirmadas">Cargando archivo...</div>
        <ul>
        @foreach($documentos as $index => $docCotFirmadas)
        @if($docCotFirmadas->tipoDocumento->id == 2)
        <li>
            @if(isset($docCotFirmadas->ruta_documento))
            <a href="#" class="text-dorado"  wire:click="descargarArchivo('{{ $docCotFirmadas->ruta_documento}}', '{{ $docCotFirmadas->nombre_documento}}')">  {{ $docCotFirmadas->nombre_documento}} 
            <button type="button" class="btn-ver">Ver</button>
            </a>
            @endif
        </li>
        @endif
        @endforeach
        </ul>
    </div>

    <div class="mt-2">
        <label for="cotizacionesPdf">Cotizaciones PDF (Pueden ir o no firmadas):</label>
        <br>
        <div wire:loading wire:target="docsCotizacionesPdf">Cargando archivo...</div>
    </div>

    <ul class="my-2">
        @foreach($documentos as $index => $docPdf)
        @if($docPdf->tipoDocumento->id == 3)
        <li>
        @if(isset($docPdf->ruta_documento))
            <a href="#" class="text-dorado"  wire:click="descargarArchivo('{{ $docPdf->ruta_documento}}', '{{ $docPdf->nombre_documento}}')">  {{ $docPdf->nombre_documento}} 
            <button type="button" class="btn-ver">Ver</button>
            </a>
        @endif
        </li>
    
        @endif
        @endforeach
    </ul>

    <div>
        <label for="anexoDocumentos">Anexo técnico u otros documentos:</label>
        <br>
        <div wire:loading wire:target="docsAnexoOtrosDocumentos">Cargando archivo...</div>
        <ul>
        @foreach($documentos as $index => $anexoDoc)
        @if($anexoDoc->tipoDocumento->id == 5)
        <li>
            @if(isset($anexoDoc->ruta_documento))
            <a href="#" class="text-dorado" wire:click="descargarArchivo('{{ $anexoDoc->ruta_documento}}', '{{ $anexoDoc->nombre_documento}}')">  {{ $anexoDoc->nombre_documento}}
            <button type="button" class="btn-ver">Ver</button>
            </a>
        @endif
        </li>
        @endif
        @endforeach
        </ul>
    </div>
    </div>
</div>