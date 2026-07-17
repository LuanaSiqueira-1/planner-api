<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMetaRequest;
use App\Http\Requests\UpdateMetaRequest;
use App\Http\Resources\MetaResource;
use App\Models\Meta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MetaController extends Controller
{
public function index(Request $request): JsonResponse
{
    $query = $request->user()
        ->metas()
        ->with('categoria');

    if ($request->filled('busca')) {
        $query->where(
            'descricao',
            'like',
            '%' . $request->string('busca') . '%'
        );
    }

    $metas = $query
        ->orderByDesc('data_inicio')
        ->orderByDesc('id')
        ->get();

    return response()->json([
        'data' => MetaResource::collection($metas)->resolve(),
    ]);
}

    public function store(StoreMetaRequest $request): JsonResponse
    {
        $meta = $request->user()
            ->metas()
            ->create($request->validated());

        $meta->load('categoria');

        return response()->json([
            'message' => 'Meta criada com sucesso.',
            'data' => MetaResource::make($meta)->resolve(),
        ], Response::HTTP_CREATED);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $meta = $this->buscarMetaDoUsuario($request, $id);

        return response()->json([
            'data' => MetaResource::make($meta)->resolve(),
        ]);
    }

    public function update(
        UpdateMetaRequest $request,
        int $id
    ): JsonResponse {
        $meta = $this->buscarMetaDoUsuario($request, $id);

        $meta->update($request->validated());
        $meta->load('categoria');

        return response()->json([
            'message' => 'Meta atualizada com sucesso.',
            'data' => MetaResource::make($meta)->resolve(),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $meta = $this->buscarMetaDoUsuario($request, $id);

        $meta->delete();

        return response()->json([
            'message' => 'Meta excluída com sucesso.',
        ]);
    }

    public function buscarPorStatus(
    Request $request,
    string $status
): JsonResponse {
    $metas = $request->user()
        ->metas()
        ->with('categoria')
        ->where('status', $status)
        ->orderByDesc('data_inicio')
        ->orderByDesc('id')
        ->get();

    return response()->json([
        'data' => MetaResource::collection($metas)->resolve(),
    ]);
}

    public function buscarPorCategoria(
    Request $request,
    string $id
): JsonResponse {
    $metas = $request->user()
        ->metas()
        ->with('categoria')
        ->where('categoria_id', $id)
        ->orderByDesc('data_inicio')
        ->orderByDesc('id')
        ->get();

    return response()->json([
        'data' => MetaResource::collection($metas)->resolve(),
    ]);
}

    public function buscarPorPeriodo(
    Request $request,
    string $periodo
): JsonResponse {
    $metas = $request->user()
        ->metas()
        ->with('categoria')
        ->where('periodo', $periodo)
        ->orderByDesc('data_inicio')
        ->orderByDesc('id')
        ->get();

    return response()->json([
        'data' => MetaResource::collection($metas)->resolve(),
    ]);
}

    public function buscarPorUsuario(int $id): JsonResponse
{
    $metas = Meta::with('categoria')
        ->where('usuario_id', $id)
        ->orderByDesc('data_inicio')
        ->orderByDesc('id')
        ->get();

    return response()->json([
        'data' => MetaResource::collection($metas)->resolve(),
    ]);
}

    private function buscarMetaDoUsuario(
        Request $request,
        int $id
    ): Meta {
        return $request->user()
            ->metas()
            ->with('categoria')
            ->findOrFail($id);
    }
}