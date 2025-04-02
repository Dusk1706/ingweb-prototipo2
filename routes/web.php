<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('register', [UsuarioController::class, 'VistaRegistro'])
->name('register');

Route::post('register', [UsuarioController::class, 'RegitrarUsuario']);

Route::get('login', [UsuarioController::class, 'VistaLogin'])
->name('login');

Route::post('login', [UsuarioController::class, 'IniciarSesion']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');