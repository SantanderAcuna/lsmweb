<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Dependencia;
use App\Models\User;
use App\Repositories\Contracts\DependenciaRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class DependenciaService implements DependenciaServiceInterface
{
    public function __construct(private readonly DependenciaRepositoryInterface $repository)
    {
    }

    public function crear(array $data, ?User $autor = null): Dependencia
    {
        return DB::transaction(function () use ($data, $autor): Dependencia {
            if ($autor !== null) {
                $data['created_by'] = $autor->id;
                $data['updated_by'] = $autor->id;
            }

            return $this->repository->create($data);
        });
    }

    public function actualizar(Dependencia $dependencia, array $data, ?User $autor = null): Dependencia
    {
        return DB::transaction(function () use ($dependencia, $data, $autor): Dependencia {
            if ($autor !== null) {
                $data['updated_by'] = $autor->id;
            }

            return $this->repository->update($dependencia, $data);
        });
    }

    public function eliminar(Dependencia $dependencia): bool
    {
        return $this->repository->delete($dependencia);
    }
}
