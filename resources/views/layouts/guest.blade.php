<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- <script src="https://kit.fontawesome.com/e1d55cc160.js" crossorigin="anonymous"></script> -->

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 fondo_degradado shadow-md overflow-hidden sm:rounded-lg">
            <div class="con">
                <a href="/">
                    <!-- <x-application-logo class=" fill-current text-gray-500" /> -->
                    <img src="{{ 'img/logos/logo_login.png' }}" alt="Image"
                        class="w-32 h-32 fill-current mx-auto mt-16">
                </a>
                <h1 class="text-blanco text-center text-2xl my-2">Sistema de Requerimientos y Solicitudes (SIRESOL)</h1>
            </div>
            <div class="mt-28">
                {{ $slot }}
            </div>
        </div>
        <footer>
            <div class="grid grid-cols-4 gap-x-10 mt-14">
                <div>
                    <img src="{{ 'img/footer/img_footer_admin.png' }}" alt="Administración Universitaria"
                    title="Administración Universitaria 2021 - 2025">
                </div>
                <div>
                    <a href="https://siea.uaemex.mx/" target="_blank">
                        <img src="{{ 'img/footer/img_footer_siea.png' }}" alt="SIEA"
                        title="Secretaría de Investigación y Estudios Avanzados">
                    </a>
                </div>
                <div>
                    <img src=" {{ 'img/footer/img_footer_dgiea.png' }} " alt="DGIEA"
                    title="Direción de Gestión de la Investigación y Estudios Avanzados">
                </div>
                <div>
                    <img src=" {{ 'img/footer/img_footer_oca.png' }} " alt="OCA"
                    title="Oficina de Conocimiento Abierto">
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
