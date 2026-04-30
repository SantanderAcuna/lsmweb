<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PqrsdResource;
use App\Models\Pqrsd;
use App\Repositories\Contracts\PqrsdRepositoryInterface;
use App\Services\PqrsdServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

final class PqrsdController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly PqrsdRepositoryInterface $repository,
        private readonly PqrsdServiceInterface $service,
    ) {
    }

    /** @return list<\Illuminate\Routing\Controllers\Middleware|string> */
    public static function middleware(): array
    {
        return [
            'auth:api',
            new Middleware('permission:panel.access'),
            new Middleware('permission:pqrsd.view', only: ['index', 'show']),
            new Middleware('permission:pqrsd.assign', only: ['asignar']),
            new Middleware('permission:pqrsd.respond', only: ['responder']),
            new Middleware('permission:pqrsd.update', only: ['cambiarEstado']),
        ];
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'tipo' => ['nullable', 'string'],
            'estado' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        return PqrsdResource::collection($this->repository->paginate($params))
            ->additional(['message' => 'Listado obtenido correctamente.']);
    }

    public function show(Pqrsd $pqrsd): JsonResponse
    {
        $pqrsd->load(['dependenciaAsignada', 'asignadoA']);

        return response()->json([
            'message' => 'Detalle obtenido correctamente.',
            'data' => new PqrsdResource($pqrsd),
        ]);
    }

    public function asignar(Request $request, Pqrsd $pqrsd): JsonResponse
    {
        $data = $request->validate([
            'asignado_a' => ['required', 'integer', Rule::exists('users', 'id')],
            'dependencia_asignada_id' => ['required', 'integer', Rule::exists('dependencias', 'id')],
        ]);

        $pqrsd = $this->service->asignar($pqrsd, $data['asignado_a'], $data['dependencia_asignada_id']);

        return response()->json([
            'message' => 'PQRSD asignada correctamente.',
            'data' => new PqrsdResource($pqrsd),
        ]);
    }

    public function responder(Request $request, Pqrsd $pqrsd): JsonResponse
    {
        $data = $request->validate([
            'respuesta' => ['required', 'string', 'min:10'],
        ]);

        $pqrsd = $this->service->responder($pqrsd, $data['respuesta'], $request->user());

        return response()->json([
            'message' => 'Respuesta registrada correctamente.',
            'data' => new PqrsdResource($pqrsd),
        ]);
    }

    public function cambiarEstado(Request $request, Pqrsd $pqrsd): JsonResponse
    {
        $data = $request->validate([
            'estado' => ['required', Rule::in(['RECIBIDA', 'EN_TRAMITE', 'RESPONDIDA', 'CERRADA', 'RECHAZADA'])],
        ]);

        $pqrsd = $this->service->cambiarEstado($pqrsd, $data['estado']);

        return response()->json([
            'message' => 'Estado actualizado.',
            'data' => new PqrsdResource($pqrsd),
        ]);
    }
}
