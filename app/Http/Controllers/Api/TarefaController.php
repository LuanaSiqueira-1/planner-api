<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tarefa;
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

    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'nullable|integer|exists:categorias,id',
            'descricao' => 'required|string|max:255',
            'status' => 'sometimes|in:CUMPRIDA,PARCIAL,NAO_CUMPRIDA',
            'data' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
            'turno' => 'required|in:MANHA,TARDE,NOITE',
            'prioridade' => 'required|in:ALTA,MEDIA,BAIXA',
        ]);

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

    public function update(Request $request, string $id)
    {
        $tarefa = Tarefa::where('usuario_id', $request->user()->id)
            ->find($id);

        if (!$tarefa) {
            return response()->json([
                'message' => 'Tarefa não encontrada',
            ], 404);
        }

       $request->validate([
        'categoria_id' => 'sometimes|nullable|integer|exists:categorias,id',
        'descricao' => 'sometimes|required|string|max:255',
        'status' => 'sometimes|required|in:CUMPRIDA,PARCIAL,NAO_CUMPRIDA',
        'data' => 'sometimes|required|date',
        'hora_inicio' => 'sometimes|required_with:hora_fim|date_format:H:i',
        'hora_fim' => 'sometimes|required_with:hora_inicio|date_format:H:i|after:hora_inicio',
        'turno' => 'sometimes|required|in:MANHA,TARDE,NOITE',
        'prioridade' => 'sometimes|required|in:ALTA,MEDIA,BAIXA',
        ]);

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