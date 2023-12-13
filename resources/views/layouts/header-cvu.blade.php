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

    <div class="grid grid-cols-3 grid-rows-2 mt-2 text-sm -mb-4">
        <div>
            <p class="">Espacio Académico:
                <span class="text-dorado">
                    {{ Session::get('name_espacioAcademico') }}
                </span>
            </p>
        </div>
        <div>
            <p>
                Responsable Técnico:
                <span class="text-dorado">
                    {{ Session::get('id_rt') }} - {{ Session::get('name_rt') }}
                </span>
            </p>
        </div>
        <div class="row-span-2 grid justify-end">
            <div class="bg-dorado/30 border-r-4 border-dorado rounded-b text-xs pl-4 py-2 w-[380px] inline-block rounded-md"
                role="alert">
                <div class="flex">
                    {{-- <div class="py-1">
                        <svg class="fill-current h-6 w-6 my-auto text-red-600 -mr-4" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20">
                            <path
                                d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                        </svg>
                    </div> --}}
                    <div class="text-start -ml-8">
                        <ul class="font-bold">
                            @if (Session::get('mensaje') != null)
                                <li class="text-blue-700">
                                    {{ Session::get('mensaje') }}
                                </li>
                            @endif
                            @if (Session::get('mensajeAdquisiciones'))
                                <li class="text-yellow-600">
                                    {{ Session::get('mensajeAdquisiciones') }}
                                </li>
                            @endif
                            @if (Session::get('mensajeSolciitudes'))
                                <li class="text-red-500">
                                    {{ Session::get('mensajeSolciitudes') }}
                                </li>
                            @endif
                            {{-- <li>{{ Session::get('mensajeSolciitudes') }}</li>
                            <li>{{ Session::get('mensajeAdquisiciones') }}</li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-2">
            <div>
                <p>Tipo de Financiamiento:
                    <span class="text-dorado">
                        {{ Session::get('tipo_financiamiento') }}
                    </span>
                </p>
            </div>
            <div>
                <p>Clave y Nombre del Proyecto:
                    <span class="text-dorado">
                        {{ Session::get('clave_dygcyn') == null ? Session::get('clave_uaem') : Session::get('clave_dygcyn') }}
                        - {{ Session::get('name_proyecto') }}
                    </span>
                </p>
            </div>
        </div>

    </div>

    {{-- <div class="sm:grid sm:grid-cols-3 sm:justify-around flex flex-wrap">
        <p class="sm:mt-2">Espacio Académico:
            <span class="text-dorado">
                {{ Session::get('name_espacioAcademico') }}
            </span>
        </p>
        <p class="sm:mt-2">Responsable Técnico: <span class="text-dorado">{{ Session::get('id_rt') }} -
                {{ Session::get('name_rt') }}</span> </p>
        <p class="sm:mt-2">Tipo de Financiamiento: <span
                class="text-dorado">{{ Session::get('tipo_financiamiento') }}</span>
        </p>
    </div>
    <div class="grid grid-cols-2 -mb-4">
        <div class="">
            <p class="mt-1">Clave y Nombre del Proyecto:
                <span class="text-dorado">
                    {{ Session::get('clave_dygcyn') == null ? Session::get('clave_uaem') : Session::get('clave_dygcyn') }}
                    - {{ Session::get('name_proyecto') }}
                </span>
            </p>
        </div>

        <div class="grid justify-end">
            <div class="bg-red-100 border-l-4 border-red-500 rounded-b text-red-500 text-xs px-4 py-2 w-96 inline-block rounded-md"
                role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="fill-current h-6 w-6 my-auto text-red-600 -mr-4" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20">
                            <path
                                d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                        </svg>
                    </div>
                    <div class="text-start">
                        <ul>
                            <li>{{ Session::get('mensaje') }}</li> 
                            <li>{{ Session::get('mensajeSolciitudes') }}</li>
                            <li>{{ Session::get('mensajeAdquisiciones') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <div class="-mb-4">
        <p class="mt-1">
            <span class="text-rojo">
                {{ Session::get('mensaje') }}<br>
                {{ Session::get('mensajeSolciitudes') }}<br>
                {{ Session::get('mensajeAdquisiciones') }}
            </span>
        </p>
    </div> --}}



</x-slot>
