
<div>
    <h1>Formulario adquisicion de bienes y servicios</h1>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
      <form wire:submit.prevent="save">
            <div>
             
                <div   class="mb-4">
                  <label for="id_rubro">
                      Rubro:
                    </label>

                  <select id="id_rubro"  name="id_rubro" wire:model= "id_rubro"  wire:change="resetearBienes">
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
                      <button type="button" wire:click='$emit("openModal", "adquisicion-description-modal", @json(["id_rubro" => $id_rubro]))'  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        +
                      </button>
                    
                </div>
       
            <div wire:poll x-data="{ elementos: @entangle('bienes').defer, id_rubro: '{{ $id_rubro }}' }">
              @if ($id_rubro != 0)
              <table class="table-auto">
                <thead>
                  <tr>
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
                  <tr >
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
                    <th><button type="button" @click='$wire.emit("openModal", "adquisicion-description-modal",  
                      { _id: elemento._id, descripcion: elemento.descripcion, cantidad: elemento.cantidad, precioUnitario: elemento.precioUnitario, iva: elemento.iva, checkIva: elemento.checkIva, importe: elemento.importe, justificacionSoftware: elemento.justificacionSoftware, 
                        numAlumnos: elemento.numAlumnos, numProfesores: elemento.numProfesores, numAdministrativos: elemento.numAdministrativos, id_rubro: id_rubro })' 
                        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                      Editar
                    </button></th>
                    <th> <button type="button" @click="elementos.splice(index, 1)" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Eliminar</button></th>
                  </tr>
                </template>
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
                </tbody>
              </table>
              @endif
           </div>

           <div class="mb-4" x-data="{ afectaSelectedOption: ''}">
            <label for="afecta">
              ¿El cambio de alguna de las características del bien descritas en la cotización,
              afectan el desarrollo de la investigación?
            </label>
            <div class="mt-2" >
              <label class="inline-flex items-center">
                <input type="radio" x-model="afectaSelectedOption" wire:model= "afecta_investigacion"  class="form-radio" name="siAfecta" value="si">
                <span class="ml-2">Si</span>
              </label>
              <label class="inline-flex items-center ml-6">
                <input type="radio" x-model="afectaSelectedOption" wire:model= "afecta_investigacion" class="form-radio" name="noAfecta" value="no" checked>
                <span class="ml-2">No</span>
              </label>
            </div>
          
            <div x-show="afectaSelectedOption === 'si'">
              <label for="justificacion">Justificación académica:</label>
              <input type="text" id="justificacion" wire:model= "justificacion_academica" class="form-input">
            </div>
          </div>

          <div class="mb-4" x-data="{ exclusividadSelectedOption: ''}">
            <label for="afecta">
              ¿Es bien o servicio con exclusividad?
            </label>
            <div class="mt-2" >
              <label class="inline-flex items-center">
                <input type="radio" x-model="exclusividadSelectedOption" wire:model= "exclusividad" class="form-radio" name="siExclusivo" value="si">
                <span class="ml-2">Si</span>
              </label>
              <label class="inline-flex items-center ml-6">
                <input type="radio" x-model="exclusividadSelectedOption" wire:model= "exclusividad" class="form-radio" name="noExclusivo" value="no" checked>
                <span class="ml-2">No</span>
              </label>
            </div>
          
            <div x-show="exclusividadSelectedOption === 'si'">
              <label for="cartaExlcusividad">Carta de exclusividad:</label>
              <input type="file" id="cartaExlcusividad"  wire:model ='docsCartaExclusividad'>
              <ul>
                @foreach ($docsCartaExclusividad as $index => $archivo)
                <li wire:key="{{ $index }}">
                    {{ $archivo->getClientOriginalName() }}
                    <button type="button" wire:click="eliminarArchivo('cartasExclusividad',{{ $index }})">Eliminar</button>
                </li>
         
              @endforeach
            </ul>
            </div>
          </div>
            <div class="mt-2">
              <label for="cotizacionFirmada">Cotización firmada:</label>
              <input type="file" id="cotizacionFirmada"  wire:model='docsCotizacionesFirmadas'   >
            </div>
            <ul>
              @foreach ($docsCotizacionesFirmadas as $index => $archivo)
              <li wire:key="{{ $index }}">
                  {{ $archivo->getClientOriginalName() }}
                  <button type="button" wire:click="eliminarArchivo('cotizacionesFirmadas',{{ $index }})">Eliminar</button>
              </li>
       
            @endforeach
          </ul>
            <div class="mt-2">
              <label for="cotizacionesPdf">Cotizaciones PDF:</label>
              <input type="file" id="cotizacionesPdf" wire:model='docsCotizacionesPdf' </>
            </div>
        
            <ul>
              @foreach ($docsCotizacionesPdf as $index => $archivo)
                      <li wire:key="{{ $index }}">
                          {{ $archivo->getClientOriginalName() }}
                          <button type="button" wire:click="eliminarArchivo('cotizacionesPdf',{{ $index }})">Eliminar</button>
                      </li>
               
              @endforeach
          </ul>
          <p>Nota: Las cotizaciones deben describir exactamente el mismo material, suministro, servicio general, 
            bien mueble o intangible.
            </p>
            <div class="mt-2">
              <input type="checkbox" id="vobo" wire:model= "vobo" name="vobo"  >
              <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del Admistrativo/Investigador</label>

            </div>
            <div>
            <button type="reset"   class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">Cancelar</button>
            
              <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ">Guardar
              </button>
              <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 ">Enviar para VoBo
              </button>
          </div>
          </div>
       </form>
</div>
