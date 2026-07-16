<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();

        return response()->json($categorias, 200);
    }

    public function store(Request $request)
    {
        $dados = $request->validate(
            [
                'nome' => ['required', 'string', 'max:255'],
                'cor' => ['bail', 'required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            ],
            [
                'nome.required' => 'O nome da categoria é obrigatório.',
                'nome.string' => 'O nome da categoria deve ser um texto.',
                'nome.max' => 'O nome da categoria deve ter no máximo 255 caracteres.',

                'cor.required' => 'A cor da categoria é obrigatória.',
                'cor.string' => 'A cor da categoria deve ser um texto.',
                'cor.regex' => 'A cor deve estar no formato hexadecimal, por exemplo: #4CAF50.',
            ]
        );

        $categoria = Categoria::create($dados);

        return response()->json([
            'message' => 'Categoria criada com sucesso',
            'categoria' => $categoria
        ], 201);
    }

    public function show(string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'message' => 'Categoria não encontrada'
            ], 404);
        }

        return response()->json($categoria, 200);
    }

    public function update(Request $request, string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'message' => 'Categoria não encontrada'
            ], 404);
        }

        if (!$request->hasAny(['nome', 'cor'])) {
            return response()->json([
                'message' => 'Informe ao menos um campo para atualizar.'
            ], 422);
        }

        $dados = $request->validate(
            [
                'nome' => ['sometimes', 'required', 'string', 'max:255'],
                'cor' => ['bail', 'sometimes', 'required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            ],
            [
                'nome.required' => 'O nome da categoria é obrigatório.',
                'nome.string' => 'O nome da categoria deve ser um texto.',
                'nome.max' => 'O nome da categoria deve ter no máximo 255 caracteres.',

                'cor.required' => 'A cor da categoria é obrigatória.',
                'cor.string' => 'A cor da categoria deve ser um texto.',
                'cor.regex' => 'A cor deve estar no formato hexadecimal, por exemplo: #4CAF50.',
            ]
        );

        $categoria->update($dados);

        return response()->json([
            'message' => 'Categoria atualizada com sucesso',
            'categoria' => $categoria
        ], 200);
    }

    public function destroy(string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'message' => 'Categoria não encontrada'
            ], 404);
        }

        $categoria->delete();

        return response()->json([
            'message' => 'Categoria excluída com sucesso'
        ], 200);
    }
}
