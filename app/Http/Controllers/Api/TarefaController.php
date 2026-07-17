<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tarefa;
use App\Http\Requests\StoreTarefaRequest;
use App\Http\Requests\UpdateTarefaRequest;
use Illuminate\Http\Request;
class TarefaController extends Controller
{
    public function index(Request $request)
    {
        $tarefas = Tarefa::with('categoria')
            ->where('usuario_id', $request->user()->id)
            ->get();

        return response()->json($tarefas, 200);
    }

    public function store(StoreTarefaRequest $request)
    {

        $tarefa = Tarefa::create([
            'usuario_id' => $request->user()->id,
            'categoria_id' => $request->categoria_id,
            'descricao' => $request->descricao,
            'status' => $request->status ?? 'NAO_CUMPRIDA',
            'data' => $request->data,
            'hora_inicio' => $request->hora_inicio,
            'hora_fim' => $request->hora_fim,
            'turno' => $request->turno,
            'prioridade' => $request->prioridade,
        ]);

        $tarefa->load('categoria');

        return response()->json([
            'message' => 'Tarefa criada com sucesso',
            'tarefa' => $tarefa,
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $tarefa = Tarefa::with('categoria')
            ->where('usuario_id', $request->user()->id)
            ->find($id);

        if (!$tarefa) {
            return response()->json([
                'message' => 'Tarefa não encontrada',
            ], 404);
        }

        return response()->json($tarefa, 200);
    }

    public function update(UpdateTarefaRequest $request, string $id)
    {
        $tarefa = Tarefa::where('usuario_id', $request->user()->id)
            ->find($id);

        if (!$tarefa) {
            return response()->json([
                'message' => 'Tarefa não encontrada',
            ], 404);
        }

$tarefa->update($request->only([
    'categoria_id',
    'descricao',
    'status',
    'data',
    'hora_inicio',
    'hora_fim',
    'turno',
    'prioridade',
]));

        $tarefa->load('categoria');

        return response()->json([
            'message' => 'Tarefa atualizada com sucesso',
            'tarefa' => $tarefa,
        ], 200);
    }

    public function destroy(Request $request, string $id)
    {
        $tarefa = Tarefa::where('usuario_id', $request->user()->id)
            ->find($id);

        if (!$tarefa) {
            return response()->json([
                'message' => 'Tarefa não encontrada',
            ], 404);
        }

        $tarefa->delete();

        return response()->json([
            'message' => 'Tarefa excluída com sucesso',
        ], 200);
    }
}