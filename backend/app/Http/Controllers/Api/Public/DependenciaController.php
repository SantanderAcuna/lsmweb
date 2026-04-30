<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\DependenciaResource;
use App\Repositories\Contracts\DependenciaRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class DependenciaController extends Controller
{
    public function __construct(private readonly DependenciaRepositoryInterface $repository)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $deps = $this->repository->arbolActivo();

        return DependenciaResource::collection($deps)
            ->additional(['message' => 'Organigrama obtenido correctamente.']);
    }
}
