<?php

use App\Http\Controllers\Api\MetaController;
use App\Http\Controllers\Api\TarefaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\LembreteController;
use Illuminate\Support\Facades\Route;

Route::post('/registrar', [AuthController::class, 'registrar']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/perfil', [AuthController::class, 'meuPerfil']);

    Route::get('/lembretes/proximos', [LembreteController::class, 'proximos']);

    Route::apiResource('lembretes', LembreteController::class);
    Route::apiResource('metas', MetaController::class);
    Route::apiResource('categorias', CategoriaController::class);
    Route::apiResource('tarefas', TarefaController::class);
});