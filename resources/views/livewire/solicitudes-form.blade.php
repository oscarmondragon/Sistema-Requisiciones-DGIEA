<div>
   
<h1>Formulario solicitudes</h1>
    <form wire:submit.prevent="save">
            <div>
             
                <div class="mb-4">
                  <label for="id_rubro">
                      Rubro:
                    </label>
                  <select id="id_rubro"  name="id_rubro" wire:model= "id_rubro" @change="$wire.resetearRecursos($event.target.selectedOptions[0].getAttribute('data-id-especial'))" >
                    <option value="0">Selecciona una opción</option>
                    @foreach ($cuentasContables as $cuentaContable)
                    <option value="{{ $cuentaContable->id }}" data-id-especial="{{ $cuentaContable->id_especial }}">{{ $cuentaContable->nombre_cuenta }}</option>
                    @endforeach
                  </select>
               </div>
               <div>
                    <label for="monto_total">Monto a solicitar</label>
                    <input type="float" id="monto_total" name="monto_total" class="form-input" >
                    @error('monto_total') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="nombre_expedido">Expedido a nombre de:</label>
                    <input type="text" readonly id="nombre_expedido" value="{{Session::get('name_rt')}}" class="form-input">
                </div>

                <div class="mb-4">
                  <label>
                    Descripción del bien o servicio:
                  </label>
                  @if ($id_rubro != 0)
                  <button type="button" x-on:click="$wire.emit('openModal', 'solicitud-recurso-modal', { 'id_rubro': {{ $id_rubro }}, 'id_rubro_especial': {{$id_rubro_especial ?: 'null'}} })" class="bg-verde w-8 h-8 py-0 px-0 rounded-full hover:bg-[#3c5042] focus:ring-2 focus:outline-none focus:ring-[#3c5042]">
                    <span class="text-white font-extrabold text-2xl">+</span>
                  </button>
                  @else
                  <p class="bg-gray-300 w-8 h-8 -pt-2 px-2 ml-1 rounded-full hover:bg-gray-200 hover:font-extrabold hover:text-gray-400 cursor-not-allowed inline-block select-none" disabled>
                    <span class="text-white font-extrabold text-2xl">+</span>
                  </p>
                  @endif
                  @error('recursos') <span class=" text-rojo error">{{ $message }}</span> @enderror
          
                </div>
            <div wire:poll x-data="{ elementos: @entangle('recursos').defer, id_rubro: '{{ $id_rubro }}', id_rubro_especial: '{{ $id_rubro_especial }}'}" }">             
              <table class="table-auto" x-show="elementos.length > 0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Importe</th>
                    <th>Concepto</th>
                    <th>Justificación</th>
                    @if ($id_rubro_especial === '2')
                    <th>fecha inicial</th>
                    <th>fecha final</th>
                    @endif
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <template x-for="(elemento, index) in elementos" :key="index">
                  <tr >
                    <th x-text="index + 1" ></th>
                    <th x-text="elemento.importe" ></th>  
                    <th x-text="elemento.concepto" ></th>
                    <th x-text="elemento.justificacionS" ></th>
                    @if ($id_rubro_especial === '2')
                      <th x-text="elemento.finicial" ></th>
                      <th x-text="elemento.ffinal" ></th>
                    @endif
                    <th>
                      <button type="button" @click='$wire.emit("openModal", "solicitud-recurso-modal",  
                      { _id: elemento._id, concepto: elemento.concepto, importe: elemento.importe, justificacionS: elemento.justificacionS, finicial: elemento.finicial, ffinal: elemento.ffinal, id_rubro: id_rubro, id_rubro_especial: id_rubro_especial })' class="hover:bg-gray-100 py-2 px-4">
                    <img src="{{ ('img/btn_editar.png') }}" alt="Image/png">
                  </button>
                  </th>
                    <th> <button type="button" @click="elementos.splice(index, 1)" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Eliminar</button></th>
                  </tr>
                </template>
                </tbody>
              </table>
           </div>

    
           @if ($id_rubro_especial === '3')        

            <div class="mt-2">
              <label for="bitacoraPdf">Bitacora (solo aplica para combustibles)</label>
              <input type="file" id="bitacoraPdf" wire:model='docsbitacoraPdf' >
            </div>
          @endif
            <ul>
              @foreach ($docsbitacoraPdf as $index => $archivo)
                      <li wire:key="{{ $index }}">
                          {{ $archivo->getClientOriginalName() }}
                          <button type="button" wire:click="eliminarArchivo('bitacoraPdf',{{ $index }})">Eliminar</button>
                      </li>
               
              @endforeach
          </ul>
          @if ($id_rubro_especial !== '3')    
            <div class="mt-2">
              <input type="checkbox" id="comprobacion"  name="comprobacion"  >
              <label for="comprobacion">Me obligo a comprobar esta cantidad en un plazo no mayor a 20 días naturales, a partir de la 
              recepción del cheque y/o transferencia, en caso contrario autorizo a la U.A.E.M. 
              para que se descuente vía nómina
              </label>
            </div>
            @endif
            <div class="mt-2">
              <input type="checkbox" id="aviso_privacidad"  name="aviso_privacidad"  >
              <label for="aviso_privacidad">Acepto aviso de privacidad simplificada de la UAEMEX</label>

            </div>


            <div class="mt-2">
              <input type="checkbox" id="vobo"  name="vobo"  >
              <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del Admistrativo/Investigador</label>

            </div>
           
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 ">Save</button>
          </div>
       </form>
</div>