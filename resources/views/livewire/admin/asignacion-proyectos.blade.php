<div>
   
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignacion de proyectos') }}
        </h2>
    </x-slot>
  
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form x-on:submit.prevent="save">
                  @csrf
                  <div>
                    <div class="my-6">
                      <label for="id_rubro">
                        Seleccione el revisor:
                      </label>
                      <select class="sm:w-auto w-full" required id="id_revisor" name="id_revisor"  >
                        <option value="0">Selecciona una opción</option>
                        @foreach ($revisores as $revisor)
                        <option value="{{ $revisor->id }}" >{{ $revisor->name}} {{ $revisor->apePaterno}} {{ $revisor->apeMaterno}}</option>
                        @endforeach
                      </select>
                      @error('id_revisor') <span class=" text-rojo">{{ $message }}</span> @enderror
                    </div>
                  </div>
                </form>
                </div>
            </div>
        </div>
 </div>
