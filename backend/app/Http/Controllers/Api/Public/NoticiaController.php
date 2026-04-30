<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoticiaResource;
use App\Repositories\Contracts\NoticiaRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class NoticiaController extends Controller
{
    public function __construct(private readonly NoticiaRepositoryInterface $repository)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'categoria' => ['nullable', 'string', 'max:80'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);
        $params['publicado'] = true;

        return NoticiaResource::collection($this->repository->paginate($params))
            ->additional(['message' => 'Noticias obtenidas correctamente.']);
    }

    public function show(string $slug): JsonResponse
    {
        $noticia = $this->repository->findBySlugPublicada($slug);

        if ($noticia === null) {
            return response()->json(['message' => 'Noticia no encontrada.'], 404);
        }

        return response()->json([
            'message' => 'Detalle obtenido correctamente.',
            'data' => new NoticiaResource($noticia),
        ]);
    }
}
