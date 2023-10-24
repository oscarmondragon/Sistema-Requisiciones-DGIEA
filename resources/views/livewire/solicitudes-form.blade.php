<?php
use Carbon\Carbon;
?>
@include('layouts.header-cvu', ['accion' => 1])
<div x-data class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 text-gray-900">
        <div class="">
<div>
  <h1 class="mt-6">Formulario solicitudes</h1>
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
      <div class="mt-8"  x-data x-init="tipoComprobacionOption = '{{ $tipo_comprobacion }}'">
        <label for="monto_total">Monto a solicitar: </label>
        <input type="number" id="monto_total" name="monto_total" wire:model="monto_total" class="inputs-formulario-solicitudes w-40" min="0" placeholder="$ 0000.00">
        @error('monto_total') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      <div class="mt-8">
        <label for="nombre_expedido">Expedido a nombre de: </label>
        <input type="text" readonly id="nombre_expedido" wire:model="nombre_expedido" class="inputs-formulario-solicitudes w-96 cursor-not-allowed" placeholder="Nombre">
        @error('nombre_expedido') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      <div class="mt-8">
        <label for="concepto"> Concepto</label>
        <input wire:model="concepto"  class="inputs-formulario-solicitudes w-80" id="concepto" type="text" placeholder="Concepto" autofocus>
        @error('concepto') <span class="text-rojo">{{ $message }}</span> @enderror
      </div>
      <div class="mt-8">
        <label for="justificacionS">Justificación</label>
        <textarea wire:model="justificacionS"  class="inputs-formulario-solicitudes w-80" rows="3" cols="30" id="justificacionS" placeholder="Justificación"></textarea>
        @error('justificacionS') <span class="text-rojo mb-4">{{ $message }}</span> @enderror
      </div>

      @if ($id_rubro_especial == '2')
      <div class="mt-8">
        <label>Periodo</label>
        <div class="mt-2 sm:grid sm:grid-cols-2 flex-col">
          <div class="sm:mr-2 mr-0 flex-col">
            <label class="block mb-1" for="finicial">
              Fecha inicial:
            </label>
            <input wire:model="finicial"  class="inputs-formulario" id="finicial" type="date" placeholder=""  min="{{ Carbon::now()->addDay(15)->format('Y-m-d') }}">
          </div>
          <div class="flex-col">
            <label class="block mb-1 sm:mt-0 mt-4" for="ffinal">
              Fecha final:
            </label>
            <input wire:model="ffinal"  class="inputs-formulario" id="ffinal" type="date" placeholder=""  min="{{ Carbon::now()->addDay(15)->format('Y-m-d') }}">
          </div>
        </div>
      @error('finicial') <span class="text-rojo block">{{ $message }}</span> @enderror
      @error('ffinal') <span class="text-rojo block">{{ $message }}</span> @enderror
      </div>
      @endif

      @if ($id_rubro_especial == '3')
      <div class="my-5">
        <label for="tipo_comprobacion" class="text-dorado font-bold">
          Tipo de solicitud
        </label>
        <div class="mt-2"><br>
          <label class=" items-center">
            <input type="radio" x-model="tipoComprobacionOption"  wire:model='tipo_comprobacion'   name="tipo_comprobacion" value="vale">
            <span class="ml-2">Vales</span>
          </label>
          <label class=" items-center ml-6">
            <input type="radio"  x-model="tipoComprobacionOption" wire:model='tipo_comprobacion'  name="tipo_comprobacion" value="ficha">
            <span class="ml-2">Ficha de gasto</span>
          </label>
        </div>
        @error('tipo_comprobacion') <span class=" text-rojo">{{ $message }}</span> @enderror

      </div>
      @endif
      @if ($id_rubro_especial == '3')
      <div class="mt-2">
        <label for="bitacoraPdf">Bitacora firmada PDF</label>
        <input type="file" id="bitacoraPdfTemp" wire:model='bitacoraPdfTemp'  accept=".pdf">
        @empty($docsCartaExclusividad)
        <label for="bitacoraPdf" class="text-dorado">Sin archivos seleccionados.</label>
        @endempty
        <br>
        <div wire:loading wire:target="docsbitacoraPdf">Cargando archivo...</div>
      
        @error('bitacoraPdfTemp') <span class=" text-rojo">{{ $message }}</span> @enderror
        @error('docsbitacoraPdf') <span class=" text-rojo">{{ $message }}</span> @enderror
        <ul>
          @foreach($docsbitacoraPdf as $index => $archivo)
          <li>
            @if(isset($archivo['datos']['ruta_documento']))
            <a href="#"  wire:click="descargarArchivo('{{ $archivo['datos']['ruta_documento'] }}')">  {{ $archivo['datos']['nombre_documento']}} Ver</a>
           @else
           {{ $archivo['datos']['nombre_documento']}}
           @endif
            <button type="button" class="btn-eliminar-lista" wire:click="eliminarArchivo('docsbitacoraPdf', {{ $index }})">
              Eliminar
            </button>
          </li>
          @endforeach
        </ul>
        @endif
      </div>
      <div class="mt-4" x-show="tipoComprobacionOption != 'vale'">
        <input type="checkbox" id="comprobacion" name="comprobacion" wire:model='comprobacion' class="mr-1">
        <label for="comprobacion">Me obligo a comprobar esta cantidad en un plazo no mayor a 20 días naturales, a partir de la
          recepción del cheque y/o transferencia, en caso contrario autorizo a la U.A.E.M.
          para que se descuente vía nómina.
        </label>
        @error('comprobacion') <span class=" text-rojo">{{ $message }}</span> @enderror
      </div>
  
      <div class="mt-4">
        <input type="checkbox" id="aviso_privacidad" name="aviso_privacidad" wire:model='aviso_privacidad' class="mr-1">
        <label for="aviso_privacidad">Acepto aviso de privacidad simplificada de la UAEMEX.</label>
        @error('aviso_privacidad') <span class=" text-rojo">{{ $message }}</span> @enderror
      </div>


      <div class="mt-4">
        <input type="checkbox" id="vobo" name="vobo" wire:model='vobo' class="mr-1">
        <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del Admistrativo/Investigador.</label>
        @error('vobo') <span class=" text-rojo">{{ $message }}</span> @enderror
      </div>

      <div class="sm:text-right text-center mt-5">
        @empty($solicitud)
        <button type="button" wire:click="save()" class="btn-success sm:w-auto w-3/4">Guardar</button>
        @endempty
        <button type="submit" class="btn-primary sm:w-auto w-3/4">Enviar para VoBo</button>
        <button type="button" class="btn-warning sm:w-auto w-3/4"  onclick="window.location.href = '{{ route('cvu.create') }}'">Cancelar</button>
      </div>
    </div>
  </form>
</div>
</div>
</div>
</div>
</div>
</div>