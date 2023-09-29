<div>
   
<h1>Formulario solicitudes</h1>
    <form wire:submit.prevent="save">
            <div>
             
                <div class="mb-4">
                  <label for="rubroS">
                      Rubro:
                    </label>
                  <select id="rubroS"  name="rubroS" wire:model= "rubroS" >
                    <option  value="51260101">COMBUSTIBLE</option>
                    <option value="51220103" >ALIMENTACIÓN PARA PERSONAS</option>
                    <option value="51370103">TRANSPORTACIÓN AÉREA</option>
                    <option value="51370104">GASTOS DE TRASLADO POR VIA TERRESTRE</option>
                    <option value="51370211">GASTOS DE ALIMENTACIÓN</option>
                    <option value="51370211">HOSPEDAJES EN TERRITORIO NACIONAL E INTERNACIONAL</option>
                  </select>
               </div>

               <div>
                    <label for="monto_total">Monto a solicitar</label>
                    <input type="float" id="monto_total" name="monto_total" class="form-input" >
                    @error('monto_total') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="nombre_expedido">Expedido a nombre de:</label>
                    <input type="text" id="nombre_expedido" class="form-input">
                </div>

                <div class="mb-4">
                    <label>
                        Desglose de recursos a solicitar
                    </label>   
                    <button type="button" wire:click='$emit("openModal", "solicitud-recurso-modal",  @json(["rubroS" => $rubroS]))'  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        +
                    </button>
                    
                </div-->
       
            <div wire:poll x-data="{ elementos: @entangle('bienes').defer, rubroS: '{{ $rubroS }}' }">             
              <table class="table-auto">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Importe</th>
                    <th>Concepto</th>
                    <th>Justificación</th>
                    @if ($rubroS === '51220103')
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
                    @if ($rubroS === '51220103')
                      <th x-text="elemento.finicial" ></th>
                      <th x-text="elemento.ffinal" ></th>
                    @endif
                    <th><button type="button" @click='$wire.emit("openModal", "solicitud-recurso-modal",  
                      { _id: elemento._id, descripcion: elemento.descripcion, monto: elemento.monto, rubroS: rubroS })' class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                      Editar
                    </button></th>
                    <th> <button type="button" @click="elementos.splice(index, 1)" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Eliminar</button></th>
                  </tr>
                </template>
                </tbody>
              </table>
           </div>

    
           @if ($rubroS === '51220103')        

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
          <p>Nota: Las cotizaciones deben describir exactamente el mismo material, suministro, servicio general, 
            bien mueble o intangible.
            </p>


            <div class="mt-2">
              <input type="checkbox" id="comprobacion"  name="comprobacion"  >
              <label for="vobo">Me obligo a comprobar esta cantidad en un plazo no mayor a 20 días naturales, a partir de la 
recepción del cheque y/o transferencia, en caso contrario autorizo a la U.A.E.M. 
para que se descuente vía nómina
</label>

            </div>
            <div class="mt-2">
              <input type="checkbox" id="aviso_privacidad"  name="aviso_privacidad"  >
              <label for="vobo">Acepto aviso de privacidad simplificada de la UAEMEX</label>

            </div>


            <div class="mt-2">
              <input type="checkbox" id="vobo"  name="vobo"  >
              <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del Admistrativo/Investigador</label>

            </div>
           
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 ">Save</button>
          </div>
       </form>
</div>