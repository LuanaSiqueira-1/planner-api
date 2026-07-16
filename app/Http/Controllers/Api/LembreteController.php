<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLembreteRequest;
use App\Http\Requests\UpdateLembreteRequest;
use App\Http\Resources\LembreteResource;
use App\Models\Lembrete;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LembreteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $lembretes = $request->user()
            ->lembretes()
            ->with('categoria')
            ->orderBy('data_hora')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => LembreteResource::collection($lembretes)->resolve(),
        ]);
    }

    public function proximos(Request $request): JsonResponse
{
    $lembretes = $request->user()
        ->lembretes()
        ->with('categoria')
        ->where('ativo', true)
        ->where('data_hora', '>=', now())
        ->orderBy('data_hora')
        ->orderBy('id')
        ->get();

    return response()->json([
        'data' => LembreteResource::collection($lembretes)->resolve(),
    ]);
}

    public function store(StoreLembreteRequest $request): JsonResponse
    {
        $dados = $request->validated();

        if (!$dados['recorrente']) {
            $dados['frequencia'] = null;
        }

        $lembrete = $request->user()
            ->lembretes()
            ->create($dados);

        $lembrete->load('categoria');

        return response()->json([
            'message' => 'Lembrete criado com sucesso.',
            'data' => LembreteResource::make($lembrete)->resolve(),
        ], Response::HTTP_CREATED);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $lembrete = $this->buscarLembreteDoUsuario($request, $id);

        return response()->json([
            'data' => LembreteResource::make($lembrete)->resolve(),
        ]);
    }

    public function update(
        UpdateLembreteRequest $request,
        int $id
    ): JsonResponse {
        $lembrete = $this->buscarLembreteDoUsuario($request, $id);

        $dados = $request->validated();

        if (!$dados['recorrente']) {
            $dados['frequencia'] = null;
        }

        $lembrete->update($dados);
        $lembrete->load('categoria');

        return response()->json([
            'message' => 'Lembrete atualizado com sucesso.',
            'data' => LembreteResource::make($lembrete)->resolve(),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $lembrete = $this->buscarLembreteDoUsuario($request, $id);

        $lembrete->delete();

        return response()->json([
            'message' => 'Lembrete excluído com sucesso.',
        ]);
    }

    private function buscarLembreteDoUsuario(
        Request $request,
        int $id
    ): Lembrete {
        return $request->user()
            ->lembretes()
            ->with('categoria')
            ->findOrFail($id);
    }
}