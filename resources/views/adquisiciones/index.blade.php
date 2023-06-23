<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Adquisiciones</title>
    @vite(['resources/css/app.scss']);
</head>
<body>
   
    <div class="container">
        <h2 class="text-center">Todas las requis BD REQUISICIONES</h2>
    <table class="table table-hover table-primary table-striped">
        <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">id</th>
              <th scope="col">id tipo</th>
              <th scope="col">id proyecto</th>
              <th scope="col">id rubro</th>
              <th scope="col">Ultima modificacion</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($adquisiciones as $adquisicion)
            <tr>
                <th scope="row">{{$loop->iteration}}</th>
                <td> {{$adquisicion->id}} </td>
                <td>{{$adquisicion->tipo_adquisicion}}</td>
                <td>{{$adquisicion->id_proyecto}}</td>
                <td>{{$adquisicion->id_rubro_requis}}</td>
                <td>{{$adquisicion->updated_at}}</td>

              </tr>
             @if ($adquisiciones == '')
                <h1>No hay requisiciones para mostrar</h1>  
             @endif
            @endforeach
          </tbody>
      </table>
    
    
    </div>
        
   
  
       
  
</body>
</html>