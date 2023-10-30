<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
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
            <form wire:submit.prevent="darVobo">
              @csrf
     
                
              <div class="mt-6">
                <label for="id_rubro">
                  Rubro:
                </label>
                <select class="w-auto" id="id_rubro" name="id_rubro" wire:model="id_rubro"  disabled>
                  <option value="0">Selecciona una opción</option>
                  @foreach ($cuentasContables as $cuentaContable)
                  <option value="{{ $cuentaContable->id }}" data-id-especial="{{ $cuentaContable->id_especial }}">{{ $cuentaContable->nombre_cuenta }}</option>
                  @endforeach
                </select>
                
              </div>
              <div class="mt-8" x-data x-init="tipoComprobacionOption = '{{ $tipo_comprobacion }}'">
                <label for="monto_total">Monto a solicitar: </label>
                <input type="number" id="monto_total" name="monto_total" wire:model="monto_total" class="inputs-formulario-solicitudes w-40" min="0" placeholder="$ 0000.00" disabled>
               
              </div>
              <div class="mt-8">
                <label for="nombre_expedido">Expedido a nombre de: </label>
                <input type="text" readonly id="nombre_expedido" wire:model="nombre_expedido" class="inputs-formulario-solicitudes sm:w-96 w-full cursor-not-allowed" placeholder="Nombre">
               
              </div>
              <div class="mt-8">
                <label for="concepto"> Concepto</label>
                <input wire:model="concepto" class="inputs-formulario-solicitudes sm:w-[477px] w-full" id="concepto" type="text" placeholder="Concepto" disabled>
               
              </div>
              <div class="mt-8">
                <label for="justificacionS">Justificación</label>
                <textarea wire:model="justificacionS" class="sm:w-3/4 w-full block" rows="3" cols="30" id="justificacionS" placeholder="Justificación" disabled></textarea>
               
              </div>

              @if ($id_rubro_especial == '2')
              <div class="mt-8">
                <label>Periodo</label>
                <div class="mt-2 sm:ml-10 sm:grid sm:grid-cols-2 gap-4 flex-col sm:w-3/4 w-full">
                  <div class="flex-col">
                    <label class="block mb-1" for="finicial">Fecha inicial:</label>
                    <input wire:model="finicial" class="inputs-formulario" id="finicial" type="date" placeholder="" min="{{ Carbon::now()->addDay(15)->format('Y-m-d') }}">
                  </div>
                  <div class="flex-col">
                    <label class="block mb-1 sm:mt-0 mt-4" for="ffinal">Fecha final:</label>
                    <input wire:model="ffinal" class="inputs-formulario" id="ffinal" type="date" placeholder="" min="{{ Carbon::now()->addDay(15)->format('Y-m-d') }}">
                  </div>
                </div>
              </div>
              @endif

              @if ($id_rubro_especial == '3')
              <div class="my-5">
                <label for="tipo_comprobacion">Tipo de solicitud</label>
                <div class="sm:ml-10 mt-2">
                  <label class=" items-center">
                    <input type="radio" x-model="tipoComprobacionOption" wire:model='tipo_comprobacion' name="tipo_comprobacion" value="vale">
                    <span class="ml-2">Vales</span>
                  </label>
                  <label class=" items-center ml-6">
                    <input type="radio" x-model="tipoComprobacionOption" wire:model='tipo_comprobacion' name="tipo_comprobacion" value="ficha">
                    <span class="ml-2">Ficha de gasto</span>
                  </label>
                </div>
              </div>
              @endif
              @if ($id_rubro_especial == '3')
              <div class="mt-2">
                <label for="bitacoraPdf">Bitacora firmada PDF</label>
                <br>
                <div wire:loading wire:target="docsbitacoraPdf">Cargando archivo...</div>
              
                @error('bitacoraPdfTemp') <span class=" text-rojo">{{ $message }}</span> @enderror
                @error('docsbitacoraPdf') <span class=" text-rojo">{{ $message }}</span> @enderror
                <ul>
                  @foreach($docsbitacoraPdf as $index => $archivo)
                  <li>
                    @if(isset($archivo['datos']['ruta_documento']))
                    <a href="#"  wire:click="descargarArchivo('{{ $archivo['datos']['ruta_documento'] }}', '{{ $archivo['datos']['nombre_documento']}}')">  {{ $archivo['datos']['nombre_documento']}} Ver</a>
                   @else
                   {{ $archivo['datos']['nombre_documento']}}
                   @endif
                    <button type="button" class="btn-eliminar-lista" wire:click="eliminarArchivo('docsbitacoraPdf', {{ $index }})">
                      Eliminar
                    </button>
                  </li>
                  @endforeach
                </ul>
              </div>
              @endif

                <div class="mt-4 sm:ml-10">
                  <input type="checkbox" id="vobo" name="vobo" wire:model='vobo' class="mr-1">
                  <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del Admistrativo/Investigador.</label>
                  @error('vobo') <span class=" text-rojo">{{ $message }}</span> @enderror
                </div>

                <div class="sm:text-right text-center mt-5">
                    <button type="submit" class="btn-primary sm:w-auto w-3/4">Confirmar VoBo</button>
                    <button type="button" class="btn-warning sm:w-auto w-3/4" x-on:click="window.location.href = '{{ route('cvu.vobo') }}'">Cancelar</button>
                </div>
 
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
