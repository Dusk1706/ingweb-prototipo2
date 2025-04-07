<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::middleware('web')->group(function () {
    Route::get('register', [UsuarioController::class, 'VistaRegistro'])
        ->name('register');

    Route::post('register', [UsuarioController::class, 'RegitrarUsuario']);


    Route::get('login', [UsuarioController::class, 'VistaLogin'])
        ->name('login');

    Route::post('login', [UsuarioController::class, 'IniciarSesion']);
});