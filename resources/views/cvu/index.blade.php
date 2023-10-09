<x-cvu-layout>
    <x-slot name="header">
        <h2 class="mb-4">
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

        <div>
            <p class="mt-2">Espacio Académico: <span class="text-dorado">{{Session::get('name_espacioAcademico')}}</span></p>
            <p class="mt-2">Clave y Nombre del Proyecto:
                <span class="text-dorado">{{Session::get('id_proyecto')}} - {{Session::get('name_proyecto')}}</span>
            </p>
            <p class="mt-2">Responsable Técnico: <span class="text-dorado">{{Session::get('id_rt')}}  - {{Session::get('name_rt')}}</span> </p>
            <p class="my-2 ">Tipo de Financiamiento: <span class="text-dorado">{{Session::get('tipo_financiamiento')}}</span> </p>
        </div>

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
    @if ($accion == 4)
    <livewire:seguimiento-siia />
    @endif

    </div>
    </div>
    </div>

</x-cvu-layout>