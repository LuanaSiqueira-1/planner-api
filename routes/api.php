<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MetaController;
use App\Http\Controllers\Api\TarefaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\LembreteController;
use Illuminate\Support\Facades\Route;

Route::post('/registrar', [AuthController::class, 'registrar']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/perfil', [AuthController::class, 'meuPerfil']);

    Route::get('/lembretes/proximos', [LembreteController::class, 'proximos']);

    Route::get('/lembretes/ativos', [LembreteController::class, 'ativos']);
    
    Route::get(
    '/lembretes/recorrentes',
    [LembreteController::class, 'recorrentes']
);
    Route::get(
    '/lembretes/data/{data}',
    [LembreteController::class, 'buscarPorData']
);
    Route::get(
    '/lembretes/usuario/{id}',
    [LembreteController::class, 'buscarPorUsuario']
);
    Route::apiResource('lembretes', LembreteController::class);
    Route::get(
    '/metas/status/{status}',
    [MetaController::class, 'buscarPorStatus']
);
    Route::get(
    '/metas/categoria/{id}',
    [MetaController::class, 'buscarPorCategoria']
);
    Route::get(
    '/metas/periodo/{periodo}',
    [MetaController::class, 'buscarPorPeriodo']
);
    Route::get(
    '/metas/usuario/{id}',
    [MetaController::class, 'buscarPorUsuario']
);
    Route::apiResource('metas', MetaController::class);
    Route::apiResource('categorias', CategoriaController::class);
    Route::get('/tarefas/status/{status}', [TarefaController::class, 'buscarPorStatus']);
    Route::get('/tarefas/categoria/{id}', [TarefaController::class, 'buscarPorCategoria']);
    Route::get('/tarefas/prioridade/{prioridade}', [TarefaController::class, 'buscarPorPrioridade']);
    Route::get('/tarefas/data/{data}', [TarefaController::class, 'buscarPorData']);
    Route::get('/tarefas/turno/{turno}', [TarefaController::class, 'buscarPorTurno']);
    Route::get('/tarefas/usuario/{id}', [TarefaController::class, 'buscarPorUsuario']);
    Route::apiResource('tarefas', TarefaController::class);
});