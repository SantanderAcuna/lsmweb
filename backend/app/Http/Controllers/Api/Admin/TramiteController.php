<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTramiteRequest;
use App\Http\Requests\Admin\UpdateTramiteRequest;
use App\Http\Resources\TramiteResource;
use App\Models\Tramite;
use App\Repositories\Contracts\TramiteRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class TramiteController extends Controller implements HasMiddleware
{
    public function __construct(private readonly TramiteRepositoryInterface $repository)
    {
    }

    /** @return list<\Illuminate\Routing\Controllers\Middleware|string> */
    public static function middleware(): array
    {
        return [
            'auth:api',
            new Middleware('permission:panel.access'),
            new Middleware('permission:tramites.view', only: ['index', 'show']),
            new Middleware('permission:tramites.create', only: ['store']),
            new Middleware('permission:tramites.update', only: ['update']),
            new Middleware('permission:tramites.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'categoria' => ['nullable', 'string', 'max:80'],
            'dependencia_id' => ['nullable', 'integer'],
            'publicado' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        return TramiteResource::collection($this->repository->paginate($params))
            ->additional(['message' => 'Listado obtenido correctamente.']);
    }

    public function store(StoreTramiteRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $tramite = $this->repository->create($data);

        return response()->json([
            'message' => 'Trámite creado exitosamente.',
            'data' => new TramiteResource($tramite),
        ], 201);
    }

    public function show(Tramite $tramite): JsonResponse
    {
        $tramite->load('dependencia');

        return response()->json([
            'message' => 'Detalle obtenido correctamente.',
            'data' => new TramiteResource($tramite),
        ]);
    }

    public function update(UpdateTramiteRequest $request, Tramite $tramite): JsonResponse
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        $tramite = $this->repository->update($tramite, $data);

        return response()->json([
            'message' => 'Trámite actualizado correctamente.',
            'data' => new TramiteResource($tramite),
        ]);
    }

    public function destroy(Tramite $tramite): JsonResponse
    {
        $this->repository->delete($tramite);

        return response()->json(['message' => 'Trámite eliminado correctamente.']);
    }
}
