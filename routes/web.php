<?php

use App\Http\Controllers\ProfileController;
use App\Http\Livewire\AdquisicionesForm;
use App\Http\Livewire\AdquisicionVobo;
use App\Http\Livewire\SolicitudVobo;
use App\Http\Livewire\SolicitudesForm;
use App\Http\Livewire\Revisores\ShowRequerimientos;
use App\Http\Livewire\Revisores\ShowRequerimientosSIIA;
use App\Http\Livewire\Admin\AsignacionProyectos;



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AdquisicionController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\SeguimientoSiiaController;
use App\Http\Controllers\CvuController;

use App\Http\Middleware\CheckRole;

require __DIR__ . '/auth.php';


//Ruta login
Route::get('/', function () {
    return view('auth.login');
});


//Rutas prueba a las bases de datos
Route::get('/proyectos-siea', [ProyectoController::class, 'home'])->name('home');
Route::get('/prueba', [AdquisicionController::class, 'prueba']);


//RUTAS MENU REVISORES Y ADMINISTRADORES
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/requerimientos-dgiea', ShowRequerimientos::class)
    ->middleware(['auth', 'verified'])->name('requerimientos.index');

Route::get('/seguimiento-siia', ShowRequerimientosSIIA::class)
    ->middleware(['auth', 'verified'])->name('requerimientos-siia.index');


Route::get('/asignacion-proyectos', AsignacionProyectos::class)
    ->middleware(['auth', 'verified', CheckRole::class . ':1,2'])->name('admin.asignacion');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//RUTAS ADQUISICIONES

Route::get('/adquisiciones/{id}/editar', AdquisicionesForm::class)->middleware('CvuAuth')->name('adquisiciones.editar');
Route::get('/adquisiciones/{id}/vobo', AdquisicionVobo::class)->middleware('CvuAuth')->name('adquisicion.vobo');




//RUTAS SOLICITUDES
Route::get('/solicitudes/{id}/editar', SolicitudesForm::class)->middleware('CvuAuth')->name('solicitudes.editar');
Route::get('/solicitudes/{id}/vobo', SolicitudVobo::class)->middleware('CvuAuth')->name('solicitud.vobo');

//RUTAS DESDE CVU

Route::post('/cvu', [CvuController::class, 'cvuVerificar'])->name('cvu.verificar');
Route::post('logout-cvu', [CvuController::class, 'destroy'])->middleware('CvuAuth')->name('logout.cvu');
Route::get('/cvu-crear', [CvuController::class, 'create'])->middleware('CvuAuth')->name('cvu.create');
Route::get('/cvu-crear-adquisiciones', AdquisicionesForm::class)->middleware('CvuAuth')->name('cvu.create-adquisiciones');
Route::get('/cvu-crear-solicitudes', SolicitudesForm::class)->middleware('CvuAuth')->name('cvu.create-solicitudes');
Route::get('/cvu-vobo', [CvuController::class, 'darVobo'])->middleware('CvuAuth')->name('cvu.vobo');
Route::get('/error-cvu', [CvuController::class, 'error'])->name('errores');
Route::get('/cvu-seguimiento', [CvuController::class, 'seguimiento'])->middleware('CvuAuth')->name('cvu.seguimiento');
Route::get('/cvu', function () {
    return redirect()->route('cvu.create');
})->middleware('CvuAuth')->name('cvu.verificado');
