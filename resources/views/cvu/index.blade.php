<x-cvu-layout>
    <x-slot name="header">
        <h2>
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
        <p>Nombre user: {{Session::get('name_user')}}</p>
        <p>Nombre administrativo: {{Session::get('id_administrativo')}}-{{Session::get('name_administrativo')}}</p>
        <p>Nombre RT: {{Session::get('id_rt')}}-{{Session::get('name_rt')}}</p>



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