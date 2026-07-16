<?php

namespace App\Http\Controllers;

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
        $dados = $request->validate(
            [
                'categoria_id' => ['nullable', 'integer', 'exists:categorias,id'],
                'descricao' => ['required', 'string', 'max:255'],
                'status' => ['sometimes', 'in:CUMPRIDA,PARCIAL,NAO_CUMPRIDA'],
                'data' => ['required', 'date'],
                'hora_inicio' => ['bail', 'required', 'date_format:H:i'],
                'hora_fim' => ['bail', 'required', 'date_format:H:i', 'after:hora_inicio'],
                'turno' => ['required', 'in:MANHA,TARDE,NOITE'],
                'prioridade' => ['required', 'in:ALTA,MEDIA,BAIXA'],
            ],
            [
                'categoria_id.integer' => 'A categoria deve ser informada por um número inteiro.',
                'categoria_id.exists' => 'A categoria informada não existe.',

                'descricao.required' => 'A descrição da tarefa é obrigatória.',
                'descricao.string' => 'A descrição da tarefa deve ser um texto.',
                'descricao.max' => 'A descrição da tarefa deve ter no máximo 255 caracteres.',

                'status.in' => 'O status deve ser CUMPRIDA, PARCIAL ou NAO_CUMPRIDA.',

                'data.required' => 'A data da tarefa é obrigatória.',
                'data.date' => 'A data da tarefa deve ser uma data válida.',

                'hora_inicio.required' => 'O horário de início é obrigatório.',
                'hora_inicio.date_format' => 'O horário de início deve estar no formato HH:mm.',

                'hora_fim.required' => 'O horário de término é obrigatório.',
                'hora_fim.date_format' => 'O horário de término deve estar no formato HH:mm.',
                'hora_fim.after' => 'O horário de término deve ser posterior ao horário de início.',

                'turno.required' => 'O turno da tarefa é obrigatório.',
                'turno.in' => 'O turno deve ser MANHA, TARDE ou NOITE.',

                'prioridade.required' => 'A prioridade da tarefa é obrigatória.',
                'prioridade.in' => 'A prioridade deve ser ALTA, MEDIA ou BAIXA.',
            ]
        );

        $dados['usuario_id'] = $request->user()->id;
        $dados['status'] = $dados['status'] ?? 'NAO_CUMPRIDA';

        $tarefa = Tarefa::create($dados);

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

        if (!$request->hasAny([
            'categoria_id',
            'descricao',
            'status',
            'data',
            'hora_inicio',
            'hora_fim',
            'turno',
            'prioridade',
        ])) {
            return response()->json([
                'message' => 'Informe ao menos um campo para atualizar.',
            ], 422);
        }

        $dados = $request->validate(
            [
                'categoria_id' => ['sometimes', 'nullable', 'integer', 'exists:categorias,id'],
                'descricao' => ['sometimes', 'required', 'string', 'max:255'],
                'status' => ['sometimes', 'required', 'in:CUMPRIDA,PARCIAL,NAO_CUMPRIDA'],
                'data' => ['sometimes', 'required', 'date'],
                'hora_inicio' => ['bail', 'sometimes', 'required_with:hora_fim', 'date_format:H:i'],
                'hora_fim' => ['bail', 'sometimes', 'required_with:hora_inicio', 'date_format:H:i', 'after:hora_inicio'],
                'turno' => ['sometimes', 'required', 'in:MANHA,TARDE,NOITE'],
                'prioridade' => ['sometimes', 'required', 'in:ALTA,MEDIA,BAIXA'],
            ],
            [
                'categoria_id.integer' => 'A categoria deve ser informada por um número inteiro.',
                'categoria_id.exists' => 'A categoria informada não existe.',

                'descricao.required' => 'A descrição da tarefa é obrigatória.',
                'descricao.string' => 'A descrição da tarefa deve ser um texto.',
                'descricao.max' => 'A descrição da tarefa deve ter no máximo 255 caracteres.',

                'status.required' => 'O status da tarefa é obrigatório.',
                'status.in' => 'O status deve ser CUMPRIDA, PARCIAL ou NAO_CUMPRIDA.',

                'data.required' => 'A data da tarefa é obrigatória.',
                'data.date' => 'A data da tarefa deve ser uma data válida.',

                'hora_inicio.required_with' => 'O horário de início deve ser informado junto com o horário de término.',
                'hora_inicio.date_format' => 'O horário de início deve estar no formato HH:mm.',

                'hora_fim.required_with' => 'O horário de término deve ser informado junto com o horário de início.',
                'hora_fim.date_format' => 'O horário de término deve estar no formato HH:mm.',
                'hora_fim.after' => 'O horário de término deve ser posterior ao horário de início.',

                'turno.required' => 'O turno da tarefa é obrigatório.',
                'turno.in' => 'O turno deve ser MANHA, TARDE ou NOITE.',

                'prioridade.required' => 'A prioridade da tarefa é obrigatória.',
                'prioridade.in' => 'A prioridade deve ser ALTA, MEDIA ou BAIXA.',
            ]
        );

        $tarefa->update($dados);
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
