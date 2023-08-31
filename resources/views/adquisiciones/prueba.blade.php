<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
      
    </head>
    <body class="bg-gray-200 py-10">
<div class="max-w-lg bg-white mx-auto p-5 rounded shadow">
      
        <form  action="cvu" method="POST" class="w-full max-w-lg">
            @csrf

        
            <div class="flex flex-wrap -mx-3 mb-2">
              <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-city">
                  Id investigador
                </label>
                <input name="id_investigador" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-city" type="text" placeholder="Albuquerque">
              </div>
              <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                <label  class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-city">
                  Id proyecto
                </label>
                <input name="id_proyecto" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-city" type="text" placeholder="Albuquerque">
              </div>
              <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-zip">
                  Id accion
                </label>
                <input name="id_accion" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-zip" type="text" placeholder="90210">
              </div>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-blue">Enviar</button>
             </div>
          </form>
        
    
</div>
    </body>
</html>
