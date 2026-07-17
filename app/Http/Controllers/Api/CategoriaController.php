<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();

        return response()->json($categorias, 200);
    }

    public function store(StoreCategoriaRequest $request)
    {
        

        $categoria = Categoria::create([
            'nome' => $request->nome,
            'cor' => $request->cor,
        ]);

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

public function update(UpdateCategoriaRequest $request, string $id)    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'message' => 'Categoria não encontrada'
            ], 404);
        }

        $categoria->update($request->only([
            'nome',
            'cor',
        ]));

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