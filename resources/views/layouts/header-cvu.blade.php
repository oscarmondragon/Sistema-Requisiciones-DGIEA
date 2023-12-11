<x-slot  name="header">
    <h2 class="-mt-3">
        @if ($accion == 1)
        {{ __('Crear requerimientos') }}

        @endif
        @if ($accion == 2)
        {{ __('Vistos Buenos') }}

        @endif
        @if ($accion == 3)
        {{ __('Seguimiento') }}

        @endif
    </h2>

    <div class="sm:grid sm:grid-cols-3 sm:justify-around flex flex-wrap">
        <p class="sm:mt-2">Espacio Académico: <span class="text-dorado">{{Session::get('name_espacioAcademico')}}</span>
        </p>
        <p class="sm:mt-2">Responsable Técnico: <span class="text-dorado">{{Session::get('id_rt')}} -
                {{Session::get('name_rt')}}</span> </p>
        <p class="sm:mt-2">Tipo de Financiamiento: <span class="text-dorado">{{Session::get('tipo_financiamiento')}}</span>
        </p>
    </div>
    <div class="-mb-4">
        <p class="mt-1">Clave y Nombre del Proyecto: 
            <span class="text-dorado">
                {{Session::get('clave_dygcyn') == null ? Session::get('clave_uaem') : Session::get('clave_dygcyn') }}
                 - {{Session::get('name_proyecto')}}
            </span>
        </p>
        <div class="mt-1 text-end">
        <p class="font-bold">Fecha limite para adquisicion de bienes y servicios: 
            <span class="text-rojo ">
                2023/06/30
            </span>
        </p>
        <p class="font-bold">Fecha limite para solicitudes de recurso: 
            <span class="text-rojo">
                2023/10/28
            </span>
        </p>
        </div>
    </div>


</x-slot>