<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\TramiteResource;
use App\Models\Tramite;
use App\Repositories\Contracts\TramiteRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class TramiteController extends Controller
{
    public function __construct(private readonly TramiteRepositoryInterface $repository)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'categoria' => ['nullable', 'string', 'max:80'],
            'dependencia_id' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);
        $params['publicado'] = true;

        return TramiteResource::collection($this->repository->paginate($params))
            ->additional(['message' => 'Catálogo de trámites obtenido correctamente.']);
    }

    public function show(string $slug): JsonResponse
    {
        $tramite = $this->repository->findBySlugPublicado($slug);

        if ($tramite === null) {
            return response()->json(['message' => 'Trámite no encontrado.'], 404);
        }

        return response()->json([
            'message' => 'Detalle obtenido correctamente.',
            'data' => new TramiteResource($tramite),
        ]);
    }
}
