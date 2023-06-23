<?php

use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AdquisicionController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/proyectos-siea', [ProyectoController::class, 'home'])->name('home');

Route::get('/adquisiciones', [AdquisicionController::class, 'index'])->name('adquisiciones.index');