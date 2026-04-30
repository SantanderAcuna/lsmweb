<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDependenciaRequest;
use App\Http\Requests\Admin\UpdateDependenciaRequest;
use App\Http\Resources\DependenciaResource;
use App\Models\Dependencia;
use App\Repositories\Contracts\DependenciaRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class DependenciaController extends Controller implements HasMiddleware
{
    public function __construct(private readonly DependenciaRepositoryInterface $repository)
    {
    }

    /** @return list<\Illuminate\Routing\Controllers\Middleware|string> */
    public static function middleware(): array
    {
        return [
            'auth:api',
            new Middleware('permission:panel.access'),
            new Middleware('permission:dependencias.view', only: ['index', 'show']),
            new Middleware('permission:dependencias.create', only: ['store']),
            new Middleware('permission:dependencias.update', only: ['update']),
            new Middleware('permission:dependencias.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'activo' => ['nullable', 'boolean'],
            'padre_id' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        return DependenciaResource::collection($this->repository->paginate($params))
            ->additional(['message' => 'Listado obtenido correctamente.']);
    }

    public function store(StoreDependenciaRequest $request): JsonResponse
    {
        $dep = $this->repository->create($request->validated());

        return response()->json([
            'message' => 'Dependencia creada exitosamente.',
            'data' => new DependenciaResource($dep),
        ], 201);
    }

    public function show(Dependencia $dependencia): JsonResponse
    {
        $dependencia->load(['padre', 'hijos']);

        return response()->json([
            'message' => 'Detalle obtenido correctamente.',
            'data' => new DependenciaResource($dependencia),
        ]);
    }

    public function update(UpdateDependenciaRequest $request, Dependencia $dependencia): JsonResponse
    {
        $dep = $this->repository->update($dependencia, $request->validated());

        return response()->json([
            'message' => 'Dependencia actualizada correctamente.',
            'data' => new DependenciaResource($dep),
        ]);
    }

    public function destroy(Dependencia $dependencia): JsonResponse
    {
        if ($dependencia->servidores()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar: la dependencia tiene servidores asignados.',
            ], 409);
        }

        $this->repository->delete($dependencia);

        return response()->json(['message' => 'Dependencia eliminada correctamente.']);
    }
}
