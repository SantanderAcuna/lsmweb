<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Sede;
use App\Models\User;
use App\Repositories\Contracts\SedeRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class SedeService implements SedeServiceInterface
{
    public function __construct(private readonly SedeRepositoryInterface $repository)
    {
    }

    public function crear(array $data, ?User $autor = null): Sede
    {
        return DB::transaction(function () use ($data, $autor): Sede {
            if ($autor !== null) {
                $data['created_by'] = $autor->id;
                $data['updated_by'] = $autor->id;
            }

            return $this->repository->create($data);
        });
    }

    public function actualizar(Sede $sede, array $data, ?User $autor = null): Sede
    {
        return DB::transaction(function () use ($sede, $data, $autor): Sede {
            if ($autor !== null) {
                $data['updated_by'] = $autor->id;
            }

            return $this->repository->update($sede, $data);
        });
    }

    public function eliminar(Sede $sede): bool
    {
        return $this->repository->delete($sede);
    }
}
