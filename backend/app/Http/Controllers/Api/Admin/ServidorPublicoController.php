<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServidorPublicoRequest;
use App\Http\Requests\Admin\UpdateServidorPublicoRequest;
use App\Http\Resources\ServidorPublicoResource;
use App\Models\ServidorPublico;
use App\Repositories\Contracts\ServidorPublicoRepositoryInterface;
use App\Services\ServidorPublicoServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class ServidorPublicoController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly ServidorPublicoRepositoryInterface $repository,
        private readonly ServidorPublicoServiceInterface $service,
    ) {
    }

    /** @return list<\Illuminate\Routing\Controllers\Middleware|string> */
    public static function middleware(): array
    {
        return [
            'auth:api',
            new Middleware('permission:panel.access'),
            new Middleware('permission:servidores.view', only: ['index', 'show']),
            new Middleware('permission:servidores.create', only: ['store']),
            new Middleware('permission:servidores.update', only: ['update']),
            new Middleware('permission:servidores.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'dependencia_id' => ['nullable', 'integer'],
            'publicado' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        return ServidorPublicoResource::collection(
            $this->repository->paginate($validated)
        )->additional(['message' => 'Listado obtenido correctamente.']);
    }

    public function store(StoreServidorPublicoRequest $request): JsonResponse
    {
        $servidor = $this->service->crear($request->validated(), $request->user());

        return response()->json([
            'message' => 'Servidor público creado exitosamente.',
            'data' => new ServidorPublicoResource($servidor),
        ], 201);
    }

    public function show(ServidorPublico $servidor): JsonResponse
    {
        $servidor->load([
            'dependencia',
            'municipioNacimiento.departamento.pais',
            'formacionesAcademicas.pais',
            'experienciasProfesionales',
        ]);

        return response()->json([
            'message' => 'Detalle obtenido correctamente.',
            'data' => new ServidorPublicoResource($servidor),
        ]);
    }

    public function update(UpdateServidorPublicoRequest $request, ServidorPublico $servidor): JsonResponse
    {
        $servidor = $this->service->actualizar($servidor, $request->validated(), $request->user());

        return response()->json([
            'message' => 'Servidor público actualizado correctamente.',
            'data' => new ServidorPublicoResource($servidor),
        ]);
    }

    public function destroy(ServidorPublico $servidor): JsonResponse
    {
        $this->service->eliminar($servidor);

        return response()->json([
            'message' => 'Servidor público eliminado correctamente.',
        ]);
    }
}
