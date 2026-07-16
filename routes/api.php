<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Route;

Route::post('/registrar', [AuthController::class, 'registrar']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/perfil', [AuthController::class, 'meuPerfil']);

    Route::apiResource('categorias', CategoriaController::class);

});