<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNoticiaRequest;
use App\Http\Requests\Admin\UpdateNoticiaRequest;
use App\Http\Resources\NoticiaResource;
use App\Models\Noticia;
use App\Repositories\Contracts\NoticiaRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class NoticiaController extends Controller implements HasMiddleware
{
    public function __construct(private readonly NoticiaRepositoryInterface $repository)
    {
    }

    /** @return list<\Illuminate\Routing\Controllers\Middleware|string> */
    public static function middleware(): array
    {
        return [
            'auth:api',
            new Middleware('permission:panel.access'),
            new Middleware('permission:noticias.view', only: ['index', 'show']),
            new Middleware('permission:noticias.create', only: ['store']),
            new Middleware('permission:noticias.update', only: ['update']),
            new Middleware('permission:noticias.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'categoria' => ['nullable', 'string', 'max:80'],
            'publicado' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        return NoticiaResource::collection($this->repository->paginate($params))
            ->additional(['message' => 'Listado obtenido correctamente.']);
    }

    public function store(StoreNoticiaRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['autor_id'] = $request->user()->id;
        $data['actualizado_por'] = $request->user()->id;

        $noticia = $this->repository->create($data);

        return response()->json([
            'message' => 'Noticia creada exitosamente.',
            'data' => new NoticiaResource($noticia),
        ], 201);
    }

    public function show(Noticia $noticia): JsonResponse
    {
        $noticia->load('autor');

        return response()->json([
            'message' => 'Detalle obtenido correctamente.',
            'data' => new NoticiaResource($noticia),
        ]);
    }

    public function update(UpdateNoticiaRequest $request, Noticia $noticia): JsonResponse
    {
        $data = $request->validated();
        $data['actualizado_por'] = $request->user()->id;

        $noticia = $this->repository->update($noticia, $data);

        return response()->json([
            'message' => 'Noticia actualizada correctamente.',
            'data' => new NoticiaResource($noticia),
        ]);
    }

    public function destroy(Noticia $noticia): JsonResponse
    {
        $this->repository->delete($noticia);

        return response()->json(['message' => 'Noticia eliminada correctamente.']);
    }
}
