<x-cvu-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if ($accion == 1)
            {{ __('Crear requerimientos') }}  

        @endif
        @if ($accion == 2)
        {{ __('Vistos Buenos') }}  

        @endif
        @if ($accion == 3)
        {{ __('Seguimiento SIIA') }}  

        @endif
        </h2>
        <p>Id user: {{Session::get('id_user')}}</p>
        <p>Id proyecto: {{Session::get('id_proyecto')}}</p>
    </x-slot>
    
            @if ($accion == 1)
                <livewire:crear-requisicion />
            @endif
            @if ($accion == 2)
                <livewire:vistos-buenos />
            @endif
            @if ($accion == 3)
                <livewire:seguimiento />
            @endif
    
        </div>
    </div>
    </div>

  </x-cvu-layout>