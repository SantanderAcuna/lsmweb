<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\SedeResource;
use App\Repositories\Contracts\SedeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class SedeController extends Controller
{
    public function __construct(private readonly SedeRepositoryInterface $repository)
    {
    }

    public function index(): AnonymousResourceCollection|JsonResponse
    {
        $sedes = $this->repository->activas();

        return SedeResource::collection($sedes)
            ->additional(['message' => 'Sedes obtenidas correctamente.']);
    }
}
