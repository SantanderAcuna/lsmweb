<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\CrearPqrsdRequest;
use App\Http\Resources\PqrsdResource;
use App\Repositories\Contracts\PqrsdRepositoryInterface;
use App\Services\PqrsdServiceInterface;
use Illuminate\Http\JsonResponse;

final class PqrsdController extends Controller
{
    public function __construct(
        private readonly PqrsdServiceInterface $service,
        private readonly PqrsdRepositoryInterface $repository,
    ) {
    }

    public function store(CrearPqrsdRequest $request): JsonResponse
    {
        $pqrsd = $this->service->radicar($request->validated());

        return response()->json([
            'message' => 'PQRSD radicada exitosamente. Conserve el número de radicado para el seguimiento.',
            'data' => [
                'radicado' => $pqrsd->radicado,
                'fecha_limite_respuesta' => $pqrsd->fecha_limite_respuesta?->toDateString(),
            ],
        ], 201);
    }

    public function consultar(string $radicado): JsonResponse
    {
        $pqrsd = $this->repository->findByRadicado($radicado);

        if ($pqrsd === null) {
            return response()->json(['message' => 'Radicado no encontrado.'], 404);
        }

        return response()->json([
            'message' => 'Consulta exitosa.',
            'data' => new PqrsdResource($pqrsd),
        ]);
    }
}
