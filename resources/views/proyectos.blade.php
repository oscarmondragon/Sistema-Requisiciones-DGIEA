<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.scss']);
</head>
<body>
   
    <div class="container">
        <h2 class="text-center">Proyectos BD SIEA</h2>
    <table class="table table-hover table-primary table-striped">
        <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Clave Uaem</th>
              <th scope="col">Nombre proyecto</th>
              <th scope="col">Tipo</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($proyectos as $proyecto)
            <tr>
                <th scope="row">{{$loop->iteration}}</th>
                <td> {{$proyecto->CvePryUaem}} </td>
                <td>{{$proyecto->NomEntPry}}</td>
                <td>{{$proyecto->Tipo_Proyecto}}</td>
              </tr>
             @if ($proyectos == '')
                <h1>No hay proyectos para mostrar</h1>  
             @endif
            @endforeach
          </tbody>
      </table>
    
    
    </div>
        
   
  
       
  
</body>
</html>