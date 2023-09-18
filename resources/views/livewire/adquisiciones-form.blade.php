<div class="my-6">
  <h1 class="text-verde text-xl font-bold ">Formulario adquisicion de bienes y servicios</h1>
  @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
  <form wire:submit.prevent="save">
    <div>

      <div class="my-6">
        <label for="rubro">
          Rubro:
        </label>
        <select id="rubro" name="rubro" wire:model="rubro" wire:change="resetearBienes" class="input_justificacion">
          <option value="0">Selecciona un opción</option>
           @foreach ($cuentasContables as $cuentaContable)
           <option value="{{ $cuentaContable->id }}">{{ $cuentaContable->nombre_cuenta }}</option>
           @endforeach
        </select>
      </div>

      <div class="mb-4">
        <label>
          Descripción del bien o servicio:
        </label>
        <button type="button" wire:click='$emit("openModal", "adquisicion-description-modal", @json(["rubro" => $rubro]))' class="bg-verde text-white font-extrabold text-center text-2xl py-2 px-4 rounded-full">
          +
        </button>

      </div>

      <div wire:poll x-data="{ elementos: @entangle('bienes').defer, rubro: '{{ $rubro }}' }">

        <table class="table-auto my-5 w-full">
          <thead>
            <tr class="bg-blanco">
              <th>#</th>
                    <th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>IVA</th>
                    <th>Importe</th>
                    @if ($id_rubro === '56590101')
                    <th>Justificacion</th>
                    <th>Alumnos</th>
                    <th>Profesores</th>
                    <th>Administrativos</th>
                    @endif
                    <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <template x-for="(elemento, index) in elementos" :key="index">
              <tr class="border border-b-textos_generales">
               <th x-text="index + 1" ></th>
                    <th x-text="elemento.descripcion" ></th>  
                    <th x-text="elemento.cantidad" ></th>
                    <th x-text="elemento.precioUnitario" ></th>
                    <th x-text="elemento.iva" ></th>
                    <th x-text="elemento.importe" ></th>
                    @if ($id_rubro === '56590101')
                    <th x-text="elemento.justificacionSoftware" ></th>
                    <th x-text="elemento.numAlumnos" ></th>
                    <th x-text="elemento.numProfesores" ></th>
                    <th x-text="elemento.numAdministrativos" ></th>
                    @endif
                <th>
                  <button type="button" @click='$wire.emit("openModal", "adquisicion-description-modal",  
                      { _id: elemento._id, descripcion: elemento.descripcion, importe: elemento.importe, justificacionSoftware: elemento.justificacionSoftware, numAlumnos: elemento.numAlumnos, numProfesores: elemento.numProfesores, numAdministrativos: elemento.numAdministrativos, rubro: rubro })' 
                      class="hover:bg-gray-100 py-2 px-4">
                    <img src="{{ ('img/btn_editar.png') }}" alt="Image/png">
                  </button>

                  <button type="button" @click="elementos.splice(index, 1)" 
                  class="hover:bg-gray-100 py-2 px-4">
                    <img src="{{ ('img/btn_eliminar.png') }}" alt="Image/png">
                  </button>
                </th>
              </tr>
               <tr>
                  @if ($id_rubro === '56590101')
                  <th></th>
                  <th></th>
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
                @if ($id_rubro === '56590101')
                <th></th>
                <th></th>
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
              <tr>
                @if ($id_rubro === '56590101')
                <th></th>
                <th></th>
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
              
            </template>
        </tbody>
         </table>
           @endif
        </div>

      <div class="mb-4" x-data="{ afectaSelectedOption: ''}">
        <label for="afecta" class="text-dorado font-bold">
          ¿El cambio de alguna de las características del bien descritas en la cotización,
          afectan el desarrollo de la investigación?
        </label>
        <div class="mt-2">
          <label class="inline-flex items-center">
            <input type="radio" x-model="afectaSelectedOption" class="form-radio rbt_formulario" name="siAfecta" value="si">
            <span class="ml-2">Si</span>
          </label>
          <label class="inline-flex items-center ml-6">
            <input type="radio" x-model="afectaSelectedOption" class="form-radio" name="noAfecta" value="no" checked>
            <span class="ml-2">No</span>
          </label>
        </div>

        <div x-show="afectaSelectedOption === 'si'">
          <label for="justificacion">Justificación académica:</label>
          <input type="text" id="justificacion" class="form-input input_justificacion w-1/2">
        </div>
      </div>

      <div class="mb-4" x-data="{ exclusividadSelectedOption: ''}">
        <label for="afecta">
          ¿Es bien o servicio con exclusividad?
        </label>
        <div class="mt-2">
          <label class="inline-flex items-center">
            <input type="radio" x-model="exclusividadSelectedOption" class="form-radio rbt_formulario" name="siExclusivo" value="si">
            <span class="ml-2">Si</span>
          </label>
          <label class="inline-flex items-center ml-6">
            <input type="radio" x-model="exclusividadSelectedOption" class="form-radio" name="noExclusivo" value="no" checked>
            <span class="ml-2">No</span>
          </label>
        </div>

        <div x-show="exclusividadSelectedOption === 'si'">
          <label for="cartaExlcusividad">Carta de exclusividad:</label>
          <input type="file" id="cartaExlcusividad" wire:model='docsCartaExclusividad' class="input_file">
          <ul>
            @foreach ($docsCartaExclusividad as $index => $archivo)
            <li wire:key="{{ $index }}">
              {{ $archivo->getClientOriginalName() }}
              <button type="button" wire:click="eliminarArchivo('cartasExclusividad',{{ $index }})" class="btn_eliminar_lista">Eliminar</button>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
      <div class="mt-2">
        <label for="cotizacionFirmada">Cotización firmada:</label>
        <input type="file" id="cotizacionFirmada" wire:model='docsCotizacionesFirmadas' class="input_file">
      </div>
      <ul>
        @foreach ($docsCotizacionesFirmadas as $index => $archivo)
        <li wire:key="{{ $index }}">
          {{ $archivo->getClientOriginalName() }}
          <button type="button" wire:click="eliminarArchivo('cotizacionesFirmadas',{{ $index }})" class="btn_eliminar_lista">Eliminar</button>
        </li>

        @endforeach
      </ul>
      <div class="my-2">
        <label for="cotizacionesPdf">Cotizaciones PDF:</label>
        <input type="file" id="cotizacionesPdf" wire:model='docsCotizacionesPdf' class="input_file">
      </div>

      <ul class="my-2">
        @foreach ($docsCotizacionesPdf as $index => $archivo)
        <li wire:key="{{ $index }}">
          {{ $archivo->getClientOriginalName() }}
          <button type="button" wire:click="eliminarArchivo('cotizacionesPdf',{{ $index }})" class="btn_eliminar_lista">Eliminar</button>
        </li>

        @endforeach
      </ul>
      <p class="text-verde"> <span class="font-bold">Nota:</span> Las cotizaciones deben describir exactamente el mismo material, suministro, servicio general,
        bien mueble o intangible.
      </p>
      <div class="my-5">
        <input type="checkbox" id="vobo" name="vobo" class="rounded-full ml-10 rbt_formulario">
        <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del Admistrativo/Investigador</label>

      </div>
      <button type="submit" class="bg-verde btn_bienes_servicios">Guardar</button>
      <button type="submit" class="bg-btn_vobo btn_bienes_servicios">Enviar para VoBo</button>
      <button type="submit" class="bg-btn_cancelar btn_bienes_servicios">Cancelar</button>
    </div>
  </form>
</div>

   
         
              