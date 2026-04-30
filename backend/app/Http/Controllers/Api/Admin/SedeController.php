<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSedeRequest;
use App\Http\Requests\Admin\UpdateSedeRequest;
use App\Http\Resources\SedeResource;
use App\Models\Sede;
use App\Repositories\Contracts\SedeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class SedeController extends Controller implements HasMiddleware
{
    public function __construct(private readonly SedeRepositoryInterface $repository)
    {
    }

    /** @return list<\Illuminate\Routing\Controllers\Middleware|string> */
    public static function middleware(): array
    {
        return [
            'auth:api',
            new Middleware('permission:panel.access'),
            new Middleware('permission:sedes.view', only: ['index', 'show']),
            new Middleware('permission:sedes.create', only: ['store']),
            new Middleware('permission:sedes.update', only: ['update']),
            new Middleware('permission:sedes.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'municipio_id' => ['nullable', 'integer'],
            'activo' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        return SedeResource::collection($this->repository->paginate($params))
            ->additional(['message' => 'Listado obtenido correctamente.']);
    }

    public function store(StoreSedeRequest $request): JsonResponse
    {
        $sede = $this->repository->create($request->validated());

        return response()->json([
            'message' => 'Sede creada exitosamente.',
            'data' => new SedeResource($sede),
        ], 201);
    }

    public function show(Sede $sede): JsonResponse
    {
        $sede->load('municipio.departamento.pais');

        return response()->json([
            'message' => 'Detalle obtenido correctamente.',
            'data' => new SedeResource($sede),
        ]);
    }

    public function update(UpdateSedeRequest $request, Sede $sede): JsonResponse
    {
        $sede = $this->repository->update($sede, $request->validated());

        return response()->json([
            'message' => 'Sede actualizada correctamente.',
            'data' => new SedeResource($sede),
        ]);
    }

    public function destroy(Sede $sede): JsonResponse
    {
        $this->repository->delete($sede);

        return response()->json(['message' => 'Sede eliminada correctamente.']);
    }
}
