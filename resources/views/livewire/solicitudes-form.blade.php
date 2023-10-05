<div>
  <h1 class="mt-4">Formulario solicitudes</h1>
  <form wire:submit.prevent="saveVobo">
    <div>

      <div class="mt-6">
        <label for="id_rubro">
          Rubro:
        </label>
        <select class="w-auto" id="id_rubro" name="id_rubro" wire:model="id_rubro" @change="$wire.resetearRecursos($event.target.selectedOptions[0].getAttribute('data-id-especial'))">
          <option value="0">Selecciona una opción</option>
          @foreach ($cuentasContables as $cuentaContable)
          <option value="{{ $cuentaContable->id }}" data-id-especial="{{ $cuentaContable->id_especial }}">{{ $cuentaContable->nombre_cuenta }}</option>
          @endforeach
        </select>
        @error('id_rubro') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      <div class="mt-8">
        <label for="monto_total">Monto a solicitar: </label>
        <input type="number" id="monto_total" name="monto_total" wire:model="monto_total" class="inputs-formulario-solicitudes w-40" min="0" placeholder="$ 0000.00">
        @error('monto_total') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      <div class="mt-8">
        <label for="nombre_expedido">Expedido a nombre de: </label>
        <input type="text" readonly id="nombre_expedido" wire:model="nombre_expedido" class="inputs-formulario-solicitudes w-96 cursor-not-allowed" placeholder="Nombre">
        @error('nombre_expedido') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>

      <div class="mt-6">
        <label>
          Descripción del bien o servicio:
        </label>
        @if ($id_rubro != 0 && $monto_total != null)
        <button type="button" x-on:click="$wire.emit('openModal', 'solicitud-recurso-modal', {'id_rubro': {{ $id_rubro }}, 
        'id_rubro_especial': {{$id_rubro_especial ? $id_rubro_especial : 'null'}}, 'monto_total': {{ $monto_total ? $monto_total : 'null' }}, 'monto_sumado': {{ $monto_sumado }} })" class="bg-verde w-8 h-8 py-0 px-0 rounded-full hover:bg-[#3c5042] focus:ring-2 focus:outline-none focus:ring-[#3c5042]">
          <span class="text-white font-extrabold text-2xl">+</span>
        </button>
        @else
        <p class="bg-gray-300 w-8 h-8 -pt-2 px-2 ml-1 rounded-full hover:bg-gray-200 hover:font-extrabold hover:text-gray-400 cursor-not-allowed inline-block select-none" disabled>
          <span class="text-white font-extrabold text-2xl">+</span>
        </p>
        @endif
        @error('recursos') <span class=" text-rojo">{{ $message }}</span> @enderror

      </div>
      <div class="overflow-x-auto mt-4" wire:poll x-data="{ elementos: @entangle('recursos').defer, id_rubro: @entangle('id_rubro').defer, id_rubro_especial: @entangle('id_rubro_especial').defer, monto_total: @entangle('monto_total').defer,monto_sumado: @entangle('monto_sumado').defer}">
        <table class="table-auto text-left text-sm w-4/5 sm:w-full mx-auto px-40" x-show="elementos.length > 0">
          <thead>
            <tr class="bg-blanco">
              <th class="w-[5%]">#</th>
              <th class="w-[10%]">Importe</th>
              <th class="w-[25%]">Concepto</th>
              <th class="w-[30%]">Justificación</th>
              @if ($id_rubro_especial === '2')
              <th class="w-[10%]">Fecha inicial</th>
              <th class="w-[10%]">Fecha final</th>
              @endif
              <th class="w-[10%]">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <template x-for="(elemento, index) in elementos" :key="index">
              <tr class="border border-b-gray-200 border-transparent">
                <th x-text="index + 1"></th>
                <th x-text="elemento.importe"></th>
                <th x-text="elemento.concepto"></th>
                <th x-text="elemento.justificacionS.substring(0,103) + '...'"></th>
                @if ($id_rubro_especial === '2')
                <th x-text="elemento.finicial"></th>
                <th x-text="elemento.ffinal"></th>
                @endif
                <th>
                  <button type="button" @click='$wire.emit("openModal", "solicitud-recurso-modal",  
                      { _id: elemento._id, concepto: elemento.concepto, importe: elemento.importe, importe_editar: elemento.importe, justificacionS: elemento.justificacionS, finicial: elemento.finicial, ffinal: elemento.ffinal, id_rubro: id_rubro, id_rubro_especial: id_rubro_especial, monto_total: monto_total, monto_sumado: monto_sumado  })' class="btn-tablas">
                    <img src="{{ ('img/btn_editar.png') }}" alt="Image/png">
                  </button>
                  <button type="button" @click.stop="elementos.splice(index, 1); $wire.deleteRecurso(elemento)" class="btn-tablas">
                    <img src="{{ ('img/btn_eliminar.png') }}" alt="Image/png">
                  </button>
                </th>
              </tr>
            </template>
          </tbody>
        </table>
      </div>


      @if ($id_rubro_especial === '3')
      <div class="mt-4">
        <label for="bitacoraPdf">Bitacora </label>
        <input type="file" id="bitacoraPdf" wire:model='docsbitacoraPdf'>
        @empty($docsbitacoraPdf)
          <label for="bitacoraPdf" class="text-dorado">
            Sin archivos seleccionados.
          </label>
        @endempty
        <br>
        <div wire:loading wire:target="docsbitacoraPdf">Cargando archivo...</div>
        @error('docsbitacoraPdf') <span class=" text-rojo">{{ $message }}</span> @enderror
      </div>
      @endif
      <ul>
        @foreach ($docsbitacoraPdf as $index => $archivo)
        <li wire:key="{{ $index }}">
          {{ $archivo->getClientOriginalName() }}
          <button type="button" wire:click="eliminarArchivo('docsbitacoraPdf',{{ $index }})" class="btn-eliminar-lista">Eliminar</button>
        </li>
        @endforeach
      </ul>
      @if ($id_rubro_especial !== '3')
      <div class="mt-4">
        <input type="checkbox" id="comprobacion" name="comprobacion" wire:model='comprobacion' class="mr-1">
        <label for="comprobacion">Me obligo a comprobar esta cantidad en un plazo no mayor a 20 días naturales, a partir de la
          recepción del cheque y/o transferencia, en caso contrario autorizo a la U.A.E.M.
          para que se descuente vía nómina
        </label>
        @error('comprobacion') <span class=" text-rojo">{{ $message }}</span> @enderror
      </div>
      @endif
      <div class="mt-4">
        <input type="checkbox" id="aviso_privacidad" name="aviso_privacidad" wire:model='aviso_privacidad' class="mr-1">
        <label for="aviso_privacidad">Acepto aviso de privacidad simplificada de la UAEMEX</label>
        @error('aviso_privacidad') <span class=" text-rojo">{{ $message }}</span> @enderror
      </div>


      <div class="mt-4">
        <input type="checkbox" id="vobo" name="vobo" wire:model='vobo' class="mr-1">
        <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del Admistrativo/Investigador</label>
        @error('vobo') <span class=" text-rojo">{{ $message }}</span> @enderror
      </div>

      <div class="sm:text-right text-center mt-5">
        <button type="button" wire:click="save()" class="btn-success sm:w-auto w-3/4">Guardar</button>
        <button type="submit" class="btn-primary sm:w-auto w-3/4">Enviar para VoBo</button>
        <button type="button" class="btn-warning sm:w-auto w-3/4" x-on:click="window.location.href = '{{ route('cvu.create') }}'">Cancelar</button>
      </div>
    </div>
  </form>
</div>