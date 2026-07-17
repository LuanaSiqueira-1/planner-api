<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LembreteResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $usuario = $request->user();
        $hoje = now()->toDateString();

        $tarefasHoje = $usuario
            ->tarefas()
            ->whereDate('data', $hoje);

        $totalTarefas = (clone $tarefasHoje)->count();

        $tarefasConcluidas = (clone $tarefasHoje)
            ->where('status', 'CUMPRIDA')
            ->count();

        $tarefasPendentes = (clone $tarefasHoje)
            ->where('status', '!=', 'CUMPRIDA')
            ->count();

        $metasEmAndamento = $usuario
            ->metas()
            ->where('status', 'EM_ANDAMENTO')
            ->count();

        $proximosLembretes = $usuario
            ->lembretes()
            ->with('categoria')
            ->where('ativo', true)
            ->where('data_hora', '>=', now())
            ->orderBy('data_hora')
            ->orderBy('id')
            ->limit(5)
            ->get();

        $indicadorProdutividade = $totalTarefas > 0
            ? round(($tarefasConcluidas / $totalTarefas) * 100, 2)
            : 0;

        return response()->json([
            'data' => [
                'tarefas_pendentes' => $tarefasPendentes,
                'tarefas_concluidas' => $tarefasConcluidas,
                'metas_em_andamento' => $metasEmAndamento,
                'proximos_lembretes' => LembreteResource::collection(
                    $proximosLembretes
                )->resolve(),
                'indicador_produtividade' => $indicadorProdutividade,
            ],
        ]);
    }
}