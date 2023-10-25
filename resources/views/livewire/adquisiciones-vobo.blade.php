<x-slot name="header">
  <h2 class="mb-4">
    {{ __('Visto Bueno') }}
  </h2>

  <div class="sm:grid sm:grid-cols-3 sm:justify-around flex flex-wrap">
    <p class="mt-2">Espacio Académico: <span class="text-dorado">{{Session::get('name_espacioAcademico')}}</span></p>
    <p class="mt-2">Responsable Técnico: <span class="text-dorado">{{Session::get('id_rt')}} - {{Session::get('name_rt')}}</span> </p>
    <p class="mt-2">Tipo de Financiamiento: <span class="text-dorado">{{Session::get('tipo_financiamiento')}}</span> </p>
  </div>
  <div>
    <p class="mt-2">Clave y Nombre del Proyecto:
      <span class="text-dorado">{{Session::get('id_proyecto')}} - {{Session::get('name_proyecto')}}</span>
    </p>
  </div>

</x-slot>

<!-- Page Content -->

<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 text-gray-900">

        <h1>Visto bueno adquisiciones</h1>
        <form wire:submit.prevent="darVobo">
          <div>
            <div class="my-6">
              <label for="id_rubro">
                Rubro:
              </label>
              <select class="w-auto" required id="id_rubro" name="id_rubro" wire:model="id_rubro" disabled >
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
              <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto" x-show="elementos.length > 0">
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
                      <th class="w-[200px]" x-text="elemento.descripcion"> </th>
                      <th class="w-[80px]" x-text="elemento.cantidad"> </th>
                      <th class="w-[80px]" x-text="elemento.precio_unitario"> </th>
                      <th class="w-[80px]" x-text="elemento.iva"> </th>
                      <th class="w-[80px]" x-text="elemento.importe"> </th>
                      @if ($id_rubro_especial == '1')
                      <th class="w-[300px]" x-text="elemento.justificacion_software.length > 85 ? elemento.justificacion_software.substring(0,85) 
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
            </div>
            <div class="my-5" x-data x-init="afectaSelectedOption = '{{ $afecta_investigacion }}'">
              <label for="afecta" class="text-dorado font-bold">
                ¿El cambio de alguna de las características del bien descritas en la cotización,
                afectan el desarrollo de la investigación?
              </label>
              <div class="mt-2">
                <label class="inline-flex items-center">
                  <input type="radio" x-model="afectaSelectedOption" wire:model='afecta_investigacion' name="siAfecta" value="1" disabled>
                  <span class="ml-2">Si</span>
                </label>
                <label class="inline-flex items-center ml-6">
                  <input type="radio" x-model="afectaSelectedOption" wire:model='afecta_investigacion'  name="noAfecta" value="0"  disabled>
                  <span class="ml-2">No</span>
                </label>
              </div>

              <div x-show="afectaSelectedOption === '1'" class="flex flex-col">
                <label for="justificacion" class="my-2">Justificación académica:</label>
                <textarea value="{{ $adquisicion->justificacion_academica }}" id="justificacion" name="justificacion" wire:model='justificacion_academica' placeholder="Justificación" class="w-3/4" rows="2" cols="30" disabled>
                </textarea>
              </div>
          </div>

          <div class="mb-2" x-data x-init="exclusividadSelectedOption = '{{ $exclusividad }}'">
            <label for="afecta">
              ¿Es bien o servicio con exclusividad?
            </label>
            <div class="mt-2">
              <label class="inline-flex items-center">
                <input type="radio" x-model="exclusividadSelectedOption" wire:model='exclusividad' name="siExclusivo" value="1" disabled>
                <span class="ml-2">Si</span>
              </label>
              <label class="inline-flex items-center ml-6">
                <input type="radio" x-model="exclusividadSelectedOption" wire:model='exclusividad' wire:click="resetdocsCartaExclusividad" name="noExclusivo" value="0"  disabled>
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
                  <a href="#"  wire:click="descargarArchivo('{{ $docCarta->ruta_documento }}', '{{ $docCarta->nombre_documento}}')">  {{ $docCarta->nombre_documento}} Ver</a>
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
                  <a href="#"  wire:click="descargarArchivo('{{ $docCotFirmadas->ruta_documento}}', '{{ $docCotFirmadas->nombre_documento}}')">  {{ $docCotFirmadas->nombre_documento}} Ver</a>
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
                  <a href="#"  wire:click="descargarArchivo('{{ $docPdf->ruta_documento}}', '{{ $docPdf->nombre_documento}}')">  {{ $docPdf->nombre_documento}} Ver</a>
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
                  <a href="#"  wire:click="descargarArchivo('{{ $anexoDoc->ruta_documento}}', '{{ $anexoDoc->nombre_documento}}')">  {{ $anexoDoc->nombre_documento}} Ver</a>
                 @endif
                </li>
                @endif
                @endforeach
              </ul>
            </div>
          </div>
          <p class="text-verde mt-5"> <span class="font-bold">Nota:</span> Las cotizaciones deben describir exactamente el mismo material, suministro, servicio general,
            bien mueble o intangible.
          </p>
          <div class="mt-10">
            <input type="checkbox" id="vobo" wire:model='vobo' name="vobo" class="rounded-full sm:ml-10">
            <label for="vobo">Dar mi visto bueno a este requerimiento.</label>
            @error('vobo') <span class=" text-rojo error">{{ $message }}</span> @enderror
          </div>
          <div class="sm:text-right text-center my-10 -mb-5">
            <button type="submit" class="btn-primary sm:w-auto w-3/4">Confirmar VoBo</button>
            <button type="button" class="btn-warning sm:w-auto w-3/4" x-on:click="window.location.href = '{{ route('cvu.create') }}'">Cancelar</button>
          </div>
      </div>
      </form>
    </div>

  </div>
</div>
</div>