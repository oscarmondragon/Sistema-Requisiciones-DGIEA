<?php

use App\Http\Controllers\ProfileController;
use App\Models\Adquisicion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AdquisicionController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\SeguimientoSiiaController;





Route::get('/', function () {
    return view('auth.login');
});


//Rutas prueba a las bases de datos
Route::get('/proyectos-siea', [ProyectoController::class, 'home'])->name('home');
Route::get('/adquisiciones-siea', [AdquisicionController::class, 'index'])->name('adquisiciones.index');


//RUTAS MENU
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/adquisiciones-dgiea', [AdquisicionController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('adquisiciones.index');

Route::get('/solicitudes-dgiea', [SolicitudController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('solicitudes.index');

Route::get('/seguimiento-siia', [SeguimientoSiiaController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('seguimiento.index');


Route::get('/asignacion-proyectos', [ProyectoController::class, 'asignacion'])
    ->middleware(['auth', 'verified'])->name('admin.asignacion');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//RUTAS ADQUISICIONES

Route::resource('adquisiciones', AdquisicionController::class);

require __DIR__ . '/auth.php';