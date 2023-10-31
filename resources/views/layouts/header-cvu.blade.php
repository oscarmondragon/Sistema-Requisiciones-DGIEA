<x-slot  name="header">
    <h2 class="-mt-3 mb-2">
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
        <p class="mt-2">Espacio Académico: <span class="text-dorado">{{Session::get('name_espacioAcademico')}}</span>
        </p>
        <p class="mt-2">Responsable Técnico: <span class="text-dorado">{{Session::get('id_rt')}} -
                {{Session::get('name_rt')}}</span> </p>
        <p class="mt-2">Tipo de Financiamiento: <span class="text-dorado">{{Session::get('tipo_financiamiento')}}</span>
        </p>
    </div>
    <div class="-mb-4">
        <p class="mt-2">Clave y Nombre del Proyecto:
            <span class="text-dorado">{{Session::get('id_proyecto')}} - {{Session::get('name_proyecto')}}</span>
        </p>
    </div>

</x-slot>