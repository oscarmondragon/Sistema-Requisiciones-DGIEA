<x-slot name="header">
  <h2 class="mb-4">
    {{ __('Editar') }}
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

        <h1>Editar adquisición</h1>
        <form wire:submit.prevent="saveVobo">
          <div>
            <div class="my-6">
              <label for="id_rubro">
                Rubro:
              </label>
              <select class="w-auto" required id="id_rubro" name="id_rubro" wire:model="id_rubro" @change="$wire.resetearBienes($event.target.selectedOptions[0].getAttribute('data-id-especial'))">
                <option value="0">Selecciona una opción</option>
                @foreach ($cuentasContables as $cuentaContable)
                <option value="{{ $cuentaContable->id }}" data-id-especial="{{ $cuentaContable->id_especial }}">{{ $cuentaContable->nombre_cuenta }}</option>
                @endforeach
              </select>
              @error('id_rubro') <span class=" text-rojo">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
              <label>
                Descripción del bien o servicio:
              </label>
              @if ($id_rubro != 0)
              <button type="button" x-on:click="$wire.emit('openModal', 'adquisicion-description-modal', { 'id_rubro': {{ $id_rubro }}, 'id_rubro_especial': {{$id_rubro_especial ?: 'null'}} })" class="bg-verde w-8 h-8 py-0 px-0 rounded-full hover:bg-[#3c5042] focus:ring-2 focus:outline-none focus:ring-[#3c5042]">
                <span class="text-white font-extrabold text-2xl">+</span>
              </button>
              @else
              <p class="bg-gray-300 w-8 h-8 -pt-2 px-2 ml-1 rounded-full hover:bg-gray-200 hover:font-extrabold hover:text-gray-400 cursor-not-allowed inline-block select-none" disabled>
                <span title="Primero selecciona un rubro." class="text-white font-extrabold text-2xl">+</span>
              </p>
              @endif
              @error('bienes') <span class=" text-rojo">{{ $message }}</span> @enderror

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
                    <th class="w-[148px]">Acciones</th>
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
                      <th class="w-[148px]">
                        <button type="button" @click='$wire.emit("openModal", "adquisicion-description-modal",
                                            { _id: elemento._id, descripcion: elemento.descripcion, cantidad: elemento.cantidad, precioUnitario: elemento.precioUnitario, 
                                              iva: elemento.iva, checkIva: elemento.checkIva, importe: elemento.importe, justificacionSoftware: elemento.justificacionSoftware,
                                              numAlumnos: elemento.numAlumnos, numProfesores: elemento.numProfesores, numAdministrativos: elemento.numAdministrativos, 
                                              id_rubro: id_rubro,
                                               id_rubro_especial: {{$id_rubro_especial ?: 'null'}} })' class="btn-tablas">
                          <img src="{{ ('/img/btn_editar.png') }}" alt="Image/png" title="Editar">
                        </button>
                        <button type="button" @click.stop="elementos.splice(index, 1); $wire.deleteBien(elemento)" class="btn-tablas">
                          <img src="{{ ('/img/btn_eliminar.png') }}" alt="Image/png" title="Eliminar">
                        </button>
                      </th>
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
                  <input type="radio" x-model="afectaSelectedOption" wire:model='afecta_investigacion' name="siAfecta" value="1">
                  <span class="ml-2">Si</span>
                </label>
                <label class="inline-flex items-center ml-6">
                  <input type="radio" x-model="afectaSelectedOption" wire:model='afecta_investigacion' wire:click="resetJustificacionAcademica" name="noAfecta" value="0" checked>
                  <span class="ml-2">No</span>
                </label>
              </div>

              <div x-show="afectaSelectedOption === '1'" class="flex flex-col">
                <label for="justificacion" class="my-2">Justificación académica:</label>
                <textarea value="{{ $adquisicion->justificacion_academica }}" id="justificacion" name="justificacion" wire:model='justificacion_academica' placeholder="Justificación" class="w-3/4" rows="2" cols="30">
                                  </textarea>
              </div>
              @error('justificacion_academica') <span class=" text-rojo">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="mb-2" x-data x-init="exclusividadSelectedOption = '{{ $exclusividad }}'">
            <label for="afecta">
              ¿Es bien o servicio con exclusividad?
            </label>
            <div class="mt-2">
              <label class="inline-flex items-center">
                <input type="radio" x-model="exclusividadSelectedOption" wire:model='exclusividad' name="siExclusivo" value="1">
                <span class="ml-2">Si</span>
              </label>
              <label class="inline-flex items-center ml-6">
                <input type="radio" x-model="exclusividadSelectedOption" wire:model='exclusividad' wire:click="resetdocsCartaExclusividad" name="noExclusivo" value="0" checked>
                <span class="ml-2">No</span>
              </label>
            </div>

            <div x-show="exclusividadSelectedOption === '1'">
              <label for="cartaExlcusividad">Carta de exclusividad:</label>
              <input type="file" id="cartaExclusividadTemp" wire:model='cartaExclusividadTemp' accept=".pdf">
              @empty($docsCartaExclusividad)
              <label for="cartaExlcusividad" class="text-dorado">Sin archivos seleccionados.</label>
              @endempty
              <br>
              <div wire:loading wire:target="docsCartaExclusividad">Cargando archivo...</div>
              @error('cartaExclusividadTemp') <span class=" text-rojo">{{ $message }}</span> @enderror
              @error('docsCartaExclusividad') <span class=" text-rojo">{{ $message }}</span> @enderror
              <ul>
                @foreach($documentos as $index => $docCarta)
                @if($docCarta->tipoDocumento->id == 1)
                <li>
                  {{ $docCarta->nombre_doc}} {{ $docCarta->nombre_documento }} {{ $docCarta->tipoDocumento->descripcion }}
                  <button type="button" class="btn-eliminar-lista" wire:click="eliminarArchivo('cartasExclusividad', {{ $index }})">
                    Eliminar
                  </button>
                </li>
                @endif
                @endforeach
              </ul>
              <!-- <table class="w-auto gap-4 text-center">
                  <thead>
                    <tr>
                      <th class="w-auto">#</th>
                      <th class="w-auto">Ruta</th>
                      <th class="w-auto">Nombre</th>
                      <th class="w-auto">Tipo</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($documentos as $docCarta)
                    <tr>
                      <td> {{ $loop->iteration}}</td>
                      <td class="text-verde"> {{ $docCarta->nombre_doc}} </td>
                      <td class="text-dorado"> {{ $docCarta->nombre_documento }} </td>
                      <td> {{ $docCarta->tipoDocumento->id == 1 }} </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table> -->
            </div>
            <div class="mt-2">
              <label for="cotizacionFirmada">Cotización PDF firmada:</label>
              <input type="file" id="cotizacionFirmadaTemp" wire:model='cotizacionFirmadaTemp' accept=".pdf">
              @empty($docsCotizacionesFirmadas)
              <label for="cotizacionFirmada" class="text-dorado">Sin archivos seleccionados.</label>
              @endempty
              <br>
              <div wire:loading wire:target="docsCotizacionesFirmadas">Cargando archivo...</div>
              <ul>
                @foreach($documentos as $index => $docCotFirmadas)
                @if($docCotFirmadas->tipoDocumento->id == 2)
                <li>
                  {{ $docCotFirmadas->nombre_doc}} {{ $docCotFirmadas->nombre_documento }} {{ $docCotFirmadas->tipoDocumento->descripcion }}
                  <button type="button" class="btn-eliminar-lista" wire:click="eliminarArchivo('cartasExclusividad', {{ $index }})">
                    Eliminar
                  </button>
                </li>
                @endif
                @endforeach
              </ul>
            </div>
            @error('cotizacionFirmadaTemp') <span class=" text-rojo">{{ $message }}</span> @enderror
            @error('docsCotizacionesFirmadas') <span class=" text-rojo">{{ $message }}</span> @enderror
            <div class="mt-2">
              <label for="cotizacionesPdf">Cotizaciones PDF (Pueden ir o no firmadas):</label>
              <input type="file" id="cotizacionPdfTemp" wire:model='cotizacionPdfTemp' accept=".pdf">
              @empty($docsCotizacionesPdf)
              <label for="cotizacionesPdf" class="text-dorado">Sin archivos seleccionados.</label>
              @endempty
              <br>
              <div wire:loading wire:target="docsCotizacionesPdf">Cargando archivo...</div>
            </div>
            @error('cotizacionPdfTemp') <span class=" text-rojo">{{ $message }}</span> @enderror
            @error('docsCotizacionesPdf') <span class=" text-rojo">{{ $message }}</span> @enderror
            <ul class="my-2">
              @foreach($documentos as $index => $docPdf)
              @if($docPdf->tipoDocumento->id == 3)
              <li>
                {{ $docPdf->nombre_doc}} {{ $docPdf->nombre_documento }} {{ $docPdf->tipoDocumento->descripcion }}
                <button type="button" class="btn-eliminar-lista" wire:click="eliminarArchivo('cotizacionesPdf', {{ $index }})">
                  Eliminar
                </button>
              </li>
              @endif
              @endforeach
            </ul>

            <div>
              <label x-show="exclusividadSelectedOption === '1'" for="anexoDocumentos" class="text-rojo mt-5 block">
                <span class="text-verde font-bold">Nota: </span>Adjunte aquí el soporte de exclusividad.
              </label>
              <label for="anexoDocumentos">Anexo técnico u otros documentos:</label>
              <input type="file" id="anexoOtroTemp" wire:model='anexoOtroTemp' accept=".pdf">
              @empty($docsAnexoOtrosDocumentos)
              <label for="anexoDocumentos" class="text-dorado">Sin archivos seleccionados.</label>
              @endempty
              <br>
              <div wire:loading wire:target="docsAnexoOtrosDocumentos">Cargando archivo...</div>
              @error('anexoOtroTemp') <span class=" text-rojo">{{ $message }}</span> @enderror
              @error('docsAnexoOtrosDocumentos') <span class=" text-rojo">{{ $message }}</span> @enderror
              <ul>
                @foreach($documentos as $index => $anexoDoc)
                @if($anexoDoc->tipoDocumento->id == 5)
                <li>
                  {{ $anexoDoc->nombre_doc}} {{ $anexoDoc->nombre_documento }} {{ $anexoDoc->tipoDocumento->descripcion }}
                  <button type="button" class="btn-eliminar-lista" wire:click="eliminarArchivo('anexoDocumentos', {{ $index }})">
                    Eliminar
                  </button>
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
            <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del Admistrativo/Investigador</label>
            @error('vobo') <span class=" text-rojo error">{{ $message }}</span> @enderror

          </div>
          <div class="sm:text-right text-center my-10 -mb-5">
            <button type="submit" class="btn-primary sm:w-auto w-3/4">Enviar para VoBo</button>
            <button type="button" class="btn-warning sm:w-auto w-3/4" x-on:click="window.location.href = '{{ route('cvu.create') }}'">Cancelar</button>
          </div>
      </div>
      </form>
    </div>

  </div>
</div>
</div>