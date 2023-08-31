<nav class="flex items-center justify-between flex-wrap bg-green-800 p-6 ">
<!--Logo de institucion-->  

    <div class="flex items-center flex-shrink-0 text-white mr-6">

                    <a href="{{ route('dashboard') }}">
                        <img class="block h-9 w-auto md:h-20 fill-current" src="{{URL::asset('home_img/uaem-dos-lineas_g.png')}}" >
                        <!--<x-application-logo class="block h-9 w-auto fill-current text-gray-800" />-->
                    </a>
                
        <span class="font-semibold text-xl tracking-tight ml-2">Sistema de solicitudes SIyEA</span>
  </div>
  <!--Boton de menu movil-->
  <div class="block w-full lg:hidden ">
    <button id="btn_menu" class="w-full flex items-center px-10 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white">
      <svg class="fill-current h-3 w-3 self-end" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
    </button>
  </div>

  <!--Este es el menú-->
  <div id= "menu"class="w-full block flex-grow lg:flex lg:items-center lg:w-auto hidden">
    <div class="text-sm lg:flex-grow sm:flex">
        <div class="space-x-8 text-gray-200 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="hover-green-300 hover:text-white !:text-gray-200">
                        {{ __('Inicio') }}
                    </x-nav-link>
                </div>

                <div class="space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('adquisiciones.index')" :active="request()->routeIs('adquisiciones.index')">
                        {{ __('Adquisiciones') }}
                    </x-nav-link>
                </div>
                <div class="space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('solicitudes.index')" :active="request()->routeIs('solicitudes.index')">
                        {{ __('Solicitudes') }}
                    </x-nav-link>
                </div>

                <div class="space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('seguimiento.index')" :active="request()->routeIs('seguimiento.index')">
                        {{ __('SIIA') }}
                    </x-nav-link>
                </div>
                <div class="space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('admin.asignacion')" :active="request()->routeIs('admin.asignacion')">
                        {{ __('Administrador') }}
                    </x-nav-link>
                </div>

    </div>
      <!--Este es el botón de download-->
    <div>
      <a href="#" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-teal-500 hover:bg-white mt-4 lg:mt-0">Download</a>
    </div>
  </div>
</nav>