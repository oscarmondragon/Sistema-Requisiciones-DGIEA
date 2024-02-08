<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-between h-auto">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('cvu.create') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('cvu.create')" :active="request()->routeIs(['cvu.create', 'cvu.create-adquisiciones', 'cvu.create-solicitudes'])">
                        {{ __('Requerimientos') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('cvu.vobo')" :active="request()->routeIs([
                        'cvu.vobo',
                        'adquisiciones.editar',
                        'solicitudes.editar',
                        'adquisicion.vobo',
                        'solicitud.vobo',
                    ])">
                        {{ __('Vistos Buenos') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('cvu.seguimiento')" :active="request()->routeIs(['cvu.seguimiento','adquisiciones.seguimiento.editar','adquisicion.ver','solicitud.ver','solicitudes.seguimiento.editar'])">
                        {{ __('Seguimiento') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('documentos.descargables')" :active="request()->routeIs(['documentos.descargables'])">
                        {{ __('Descargables') }}
                    </x-nav-link>
                </div>

            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center mt-4 flex-wrap ">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <div>
                            <p class="md:text-sm"><span class="font-bold">Fecha: </span>{{ date('d/m/Y') }}</p>
                        </div>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout.cvu') }}">
                            @csrf
                            <div>
                                <div class="inline-block">
                                    <p class="md:text-sm -mr-5"><span class="font-bold">En sesión:
                                        </span>{{ Session::get('name_user') }}</p>
                                </div>
                                <button class="inline-block py-0 px-0 border border-transparent hover:bg-transparent"
                                    title="Salir">
                                    <x-dropdown-link :href="route('logout.cvu')"
                                        onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        <div class="inline-block">
                                            <img src="{{ asset('img/botones/btn_salir.png') }}" alt="Botón Salir" title="Salir">
                                        </div>
                                    </x-dropdown-link>
                                </button>
                            </div>
                        </form>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <!-- si quito este x-slot me manda un error, por eso lo deje vacio -->
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Settings Dropdown -->
            {{-- <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-red-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div><p>En sesión: {{Session::get('name_user')}}</p>
        </div>

        <div class="ml-1">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
        </button>
        </x-slot>

        <x-slot name="content">
            <x-dropdown-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-dropdown-link>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </x-slot>
        </x-dropdown>
    </div> --}}

            <!-- Hamburger -->

            <div class="-mr-2 flex flex-nowrap items-center sm:hidden">

                <div class="sm:hidden inline-block my-auto text-end">
                    <p class="flex">{{ Session::get('name_user') }}
                        <p class="text-verde font-bold text-sm text-end">En sesión</p>
                    </p>
                </div>
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                    title="Opciones">

                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('cvu.create')" :active="request()->routeIs('cvu.create')">
                {{ __('Requerimientos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cvu.vobo')" :active="request()->routeIs('cvu.vobo')">
                {{ __('Vistos Buenos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cvu.seguimiento')" :active="request()->routeIs('cvu.seguimiento')">
                {{ __('Seguimiento') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('documentos.descargables')" :active="request()->routeIs('documentos.descargables')">
                {{ __('Descargables') }}
            </x-responsive-nav-link>
            <div>
                <form method="POST" action="{{ route('logout.cvu') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout.cvu')"
                        onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                        {{ __('Salir') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        {{-- <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}
    </div>
    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
    </div>

    <div class="mt-3 space-y-1">
        <x-responsive-nav-link :href="route('profile.edit')">
            {{ __('Profile') }}
        </x-responsive-nav-link>

        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                {{ __('Log Out') }}
            </x-responsive-nav-link>
        </form>
    </div>
    </div> --}}
    </div>
</nav>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dropdown', () => ({
            open: false,

            toggle() {
                this.open = !this.open
            },
        }))
    })
</script>
