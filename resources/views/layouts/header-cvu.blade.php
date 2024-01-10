<x-slot name="header">
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
        <p class="sm:mt-2">Espacio Académico: <span class="text-dorado">{{ Session::get('name_espacioAcademico') }}</span>
        </p>
        <p class="sm:mt-2">Responsable Técnico: <span class="text-dorado">{{ Session::get('id_rt') }} -
                {{ Session::get('name_rt') }}</span> </p>
        <p class="sm:mt-2">Tipo de Financiamiento: <span
                class="text-dorado">{{ Session::get('tipo_financiamiento') }}</span>
        </p>
    </div>
    <div class="mt-2">
        <p class="mt-1">Clave y Nombre del Proyecto:
            <span class="text-dorado">
                {{ Session::get('clave_dygcyn') == null ? Session::get('clave_uaem') : Session::get('clave_dygcyn') }}
                - {{ Session::get('name_proyecto') }}
            </span>
        </p>
    </div>

    {{-- <div class="-mb-10 mt-4">
        <p>
            <span class="text-rojo mt-4 bg-yellow-100">
                {{ Session::get('mensaje') }}<br>
                {{ Session::get('mensajeSolciitudes') }}<br>
                {{ Session::get('mensajeAdquisiciones') }}
            </span>
        </p>
    </div> --}}

    <div class="mt-1 -mb-3">
        @if (Session::get('mensajeAdquisiciones'))
            @if (Str::containsAll(Session::get('mensajeAdquisiciones'), ['Te', 'quedan']))
                <span class="text-green-500 bg-green-100 py-1 sm:px-2 rounded-md">
                    {{ Session::get('mensajeAdquisiciones') }}
                </span> <br>
            @else
                <span class="text-red-500 bg-red-100 py-1 sm:px-2 rounded-md">
                    {{ Session::get('mensajeAdquisiciones') }}
                </span> <br>
            @endif
        @endif

        @if (Session::get('mensajeSolciitudes'))
            @if (Str::containsAll(Session::get('mensajeSolciitudes'), ['Te', 'quedan']))
                <span class="text-green-500 bg-green-100 py-1 sm:px-2 rounded-md mb-20">
                    {{ Session::get('mensajeSolciitudes') }}
                </span> <br>
            @else
                <span class="text-red-500 bg-red-100 py-1 sm:px-2 rounded-md mb-20">
                    {{ Session::get('mensajeSolciitudes') }}
                </span> <br>
            @endif
        @endif

        @if (Session::get('mensaje'))
            <span class="text-orange-500 bg-orange-100 py-1 sm:px-2 rounded-md">
                {{ Session::get('mensaje') }}
            </span>
        @endif
    </div>


</x-slot>
