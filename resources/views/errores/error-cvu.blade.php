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

<body>
    <div class="grid h-screen place-items-center text-center">
        <div>
            <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-dorado">404</h1>
            <p class="mb-4 text-3xl tracking-tight font-bold text-verde md:text-4xl">Esta página no se encuentra en nuestro sistema.</p>
            <p class="mb-4 text-lg font-light text-verde">La página a la que intentas acceder no existe o se ha movido.
                <br>Vuelve a la página principal.
            </p>
            <a href="http://sistema-requisiciones-dgiea.test/prueba" class="bg-dorado text-white inline-flex bg-primary-600 hover:bg-primary-800 focus:ring-4 focus:outline-none 
                    focus:ring-dorado font-medium rounded-lg text-sm px-5 py-2.5 text-center my-4">Volver a inicio</a>
        </div>
    </div>
</body>

</html>