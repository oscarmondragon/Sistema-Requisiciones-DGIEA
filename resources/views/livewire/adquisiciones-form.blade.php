
<div>
    <h1>Formulario adquisicion de bienes y servicios</h1>
      <form wire:submit.prevent="save">
            <div>
             
                <div class="mb-4">
                  <label for="rubro">
                      Rubro:
                    </label>
                  <select id="rubro"  name="rubro" wire:model= "rubro"  wire:change="resetearBienes">
                    <option  value="1">PAPELERIA Y ARTICULOS DE ESCRITORIO</option>
                    <option value="2" >MATERIAL PARA COMPUTADORAS Y BIENES INFORMATICOS</option>
                    <option value="3">LICENCIAMIENTO DE SOFWARE</option>
                  </select>
               </div>

                <div class="mb-4">
                    <label>
                        Descripción del bien o servicio:
                      </label>   
                      <button type="button" wire:click='$emit("openModal", "adquisicion-description-modal", @json(["rubro" => $rubro]))'  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        +
                      </button>
                    
                </div>
       
            <div wire:poll x-data="{ elementos: @entangle('bienes').defer, rubro: '{{ $rubro }}' }">
             
              <table class="table-auto">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Descripcion</th>
                    <th>Importe</th>
                    @if ($rubro === '3')
                    <th>Justificacion</th>
                    <th>Alumnos</th>
                    <th>Profesoeres</th>
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
                    <th x-text="elemento.importe" ></th>
                    @if ($rubro === '3')
                    <th x-text="elemento.justificacionSoftware" ></th>
                    <th x-text="elemento.numAlumnos" ></th>
                    <th x-text="elemento.numProfesores" ></th>
                    <th x-text="elemento.numAdministrativos" ></th>
                    @endif
                    <th><button type="button" @click='$wire.emit("openModal", "adquisicion-description-modal",  
                      { _id: elemento._id, descripcion: elemento.descripcion, importe: elemento.importe, justificacionSoftware: elemento.justificacionSoftware, numAlumnos: elemento.numAlumnos, numProfesores: elemento.numProfesores, numAdministrativos: elemento.numAdministrativos, rubro: rubro })' class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                      Editar
                    </button></th>
                    <th> <button type="button" @click="elementos.splice(index, 1)" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Eliminar</button></th>
                  </tr>
                </template>
                </tbody>
              </table>
              
           </div>

           <div class="mb-4" x-data="{ afectaSelectedOption: ''}">
            <label for="afecta">
              ¿El cambio de alguna de las características del bien descritas en la cotización,
              afectan el desarrollo de la investigación?
            </label>
            <div class="mt-2" >
              <label class="inline-flex items-center">
                <input type="radio" x-model="afectaSelectedOption" class="form-radio" name="siAfecta" value="si">
                <span class="ml-2">Si</span>
              </label>
              <label class="inline-flex items-center ml-6">
                <input type="radio" x-model="afectaSelectedOption" class="form-radio" name="noAfecta" value="no" checked>
                <span class="ml-2">No</span>
              </label>
            </div>
          
            <div x-show="afectaSelectedOption === 'si'">
              <label for="justificacion">Justificación académica:</label>
              <input type="text" id="justificacion" class="form-input">
            </div>
          </div>

          <div class="mb-4" x-data="{ exclusividadSelectedOption: ''}">
            <label for="afecta">
              ¿Es bien o servicio con exclusividad?
            </label>
            <div class="mt-2" >
              <label class="inline-flex items-center">
                <input type="radio" x-model="exclusividadSelectedOption" class="form-radio" name="siExclusivo" value="si">
                <span class="ml-2">Si</span>
              </label>
              <label class="inline-flex items-center ml-6">
                <input type="radio" x-model="exclusividadSelectedOption" class="form-radio" name="noExclusivo" value="no" checked>
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
              <input type="checkbox" id="vobo"  name="vobo"  >
              <label for="vobo">VoBo al requerimiento solicitado. Se envía para VoBo del Admistrativo/Investigador</label>

            </div>
           
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 ">Save</button>
          </div>
       </form>
</div>
