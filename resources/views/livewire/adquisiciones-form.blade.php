<div class="my-6">
  @if ($errors->any())
  <div class="p-4 my-4 rounded-lg bg-red-50 dark:text-rojo text-rojo">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif
  <h1>Formulario adquisición de bienes y servicios</h1>
  <!-- @if(session('error'))
  <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
    <span class="font-medium"> {{ session('error') }} </span>
  </div>
  @endif -->
  <form wire:submit.prevent="saveVobo">
    <div>
      <div class=" my-6">
        <label for="id_rubro">
          Rubro:
        </label>
        <select class="w-3/4" required id="id_rubro" name="id_rubro" wire:model="id_rubro" @change="$wire.resetearBienes($event.target.selectedOptions[0].getAttribute('data-id-especial'))">
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
          <span class="text-white font-extrabold text-2xl">+</span>
        </p>
        @endif
        @error('bienes') <span class=" text-rojo">{{ $message }}</span> @enderror

      </div>

      <div class="overflow-x-auto" wire:poll x-data="{ elementos: @entangle('bienes').defer, id_rubro: '{{ $id_rubro }}' }">
        <table class="table-auto text-left text-sm w-3/4 sm:w-full mx-auto" x-show="elementos.length > 0">
          <thead>
            <tr class="bg-blanco">
              <th class="w-[26px]">#</th>
              <th class="w-[200px]">Descripcion</th>
              <th class="w-[80px]">Cantidad</th>
              <th class="w-[80px]">Precio Unitario</th>
              <th class="w-[80px]">IVA</th>
              <th class="w-[80px]">Importe</th>
              @if ($id_rubro_especial == '1')
              <th class="w-[300px]">Justificacion</th>
              <th class="w-[180px]">Beneficiados</th>
              @endif
              <th class="w-[148px]">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <template x-for="(elemento, index) in elementos" :key="index">
              <tr class="border border-b-gray-200 border-transparent">
                <th class="w-[26px]" x-text="index + 1"></th>
                <th class="w-[200px]" x-text="elemento.descripcion"></th>
                <th class="w-[80px]" x-text="elemento.cantidad"></th>
                <th class="w-[80px]" x-text="elemento.precioUnitario"></th>
                <th class="w-[80px]" x-text="elemento.iva"></th>
                <th class="w-[80px]" x-text="elemento.importe"></th>
                @if ($id_rubro_especial == '1')
                <th class="w-[300px]" x-text="elemento.justificacionSoftware.substring(0,75) + '...'"></th>
                <th class="w-[180px]" x-html="'Alumnos: ' + elemento.numAlumnos + 
                          '<br>Profesores: ' + elemento.numProfesores + 
                          '<br>Administrativos: ' + elemento.numAdministrativos"></th>
                @endif
                <th class="w-[148px]">
                  <button type="button" @click='$wire.emit("openModal", "adquisicion-description-modal",  
                      { _id: elemento._id, descripcion: elemento.descripcion, cantidad: elemento.cantidad, precioUnitario: elemento.precioUnitario, iva: elemento.iva, checkIva: elemento.checkIva, importe: elemento.importe, justificacionSoftware: elemento.justificacionSoftware, 
                        numAlumnos: elemento.numAlumnos, numProfesores: elemento.numProfesores, numAdministrativos: elemento.numAdministrativos, id_rubro: id_rubro,
                         id_rubro_especial: {{$id_rubro_especial ?: 'null'}} })' class="btn-tablas">
                    <img src="{{ ('img/btn_editar.png') }}" alt="Image/png">
                  </button>
                  <button type="button" @click.stop="elementos.splice(index, 1); $wire.deleteBien(elemento)" class="btn-tablas">
                    <img src="{{ ('img/btn_eliminar.png') }}" alt="Image/png">
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
              <th>${{$subtotal}}</th>
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
              <th>${{$iva}}</th>
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
              <th>${{$total}}</th>
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
          <textarea id="justificacion" name="justificacion" wire:model='justificacion_academica' placeholder="Justificación" class="w-3/4" rows="2" cols="30">
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
        <input type="file" id="cartaExlcusividad" wire:model='docsCartaExclusividad'>
        @empty($docsCartaExclusividad)<span class="text-dorado">Sin archivos seleccionados.</span>
        @endempty
        <br>
        <div wire:loading wire:target="docsCartaExclusividad">Cargando archivo...</div>
        @error('docsCartaExclusividad') <span class=" text-rojo">{{ $message }}</span> @enderror
        <ul>
          @foreach($docsCartaExclusividad as $index => $docCarta)
          <li>
            {{ $docCarta->getClientOriginalName()}}
            <button type="button" class="btn-eliminar-lista" wire:click="eliminarArchivo('cartasExclusividad', {{ $index }})">
              Eliminar
            </button>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
    <div class="mt-2">
      <label for="cotizacionFirmada">Cotización firmada:</label>
      <input type="file" id="cotizacionFirmada" wire:model='docsCotizacionesFirmadas'>
      @empty($docsCotizacionesFirmadas)<span class="text-dorado">Sin archivos seleccionados.</span>@endempty
      <br>
      <div wire:loading wire:target="docsCotizacionesFirmadas">Cargando archivo...</div>
    </div>
    @error('docsCotizacionesFirmadas') <span class=" text-rojo">{{ $message }}</span> @enderror
    <ul>
      @foreach($docsCotizacionesFirmadas as $index => $docFirmadas)
      <li>
        {{ $docFirmadas->getClientOriginalName()}}
        <button type="button" class="btn-eliminar-lista" wire:click="eliminarArchivo('cotizacionesFirmadas', {{ $index }})">
          Eliminar
        </button>
      </li>
      @endforeach
    </ul>
    <div class="mt-2">
      <label for="cotizacionesPdf">Cotizaciones PDF:</label>
      <input type="file" id="cotizacionesPdf" wire:model='docsCotizacionesPdf'>
      @empty($docsCotizacionesPdf)<span class="text-dorado">Sin archivos seleccionados.</span>@endempty
      <br>
      <div wire:loading wire:target="docsCotizacionesPdf">Cargando archivo...</div>
    </div>
    @error('docsCotizacionesPdf') <span class=" text-rojo">{{ $message }}</span> @enderror
    <ul class="my-2">
      @foreach($docsCotizacionesPdf as $index => $docPdf)
      <li>
        {{ $docPdf->getClientOriginalName()}}
        <button type="button" class="btn-eliminar-lista" wire:click="eliminarArchivo('cotizacionesPdf', {{ $index }})">
          Eliminar
        </button>
      </li>
      @endforeach
    </ul>
    <p class="text-verde mt-5"> <span class="font-bold">Nota:</span> Las cotizaciones deben describir exactamente el mismo material, suministro, servicio general,
      bien mueble o intangible.
    </p>
    <div class="mt-10">
      <input type="checkbox" id="vobo" wire:model='vobo' name="vobo" class="rounded-full ml-10">
      <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del Admistrativo/Investigador</label>
      @error('vobo') <span class=" text-rojo error">{{ $message }}</span> @enderror

    </div>
    <div class="sm:text-right text-left my-10 -mb-5">
      <button type="button" wire:click="save()" class="btn-success">Guardar</button>
      <button type="submit" class="btn-primary">Enviar para VoBo</button>
      <button type="button" class="btn-warning" x-on:click="window.location.href = '{{ route('cvu.create') }}'">Cancelar</button>
    </div>
</div>
</form>
</div>