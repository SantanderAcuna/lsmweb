<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tramite;
use App\Models\User;
use App\Repositories\Contracts\TramiteRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class TramiteService implements TramiteServiceInterface
{
    public function __construct(private readonly TramiteRepositoryInterface $repository)
    {
    }

    public function crear(array $data, ?User $autor = null): Tramite
    {
        return DB::transaction(function () use ($data, $autor): Tramite {
            if ($autor !== null) {
                $data['created_by'] = $autor->id;
                $data['updated_by'] = $autor->id;
            }

            return $this->repository->create($data);
        });
    }

    public function actualizar(Tramite $tramite, array $data, ?User $autor = null): Tramite
    {
        return DB::transaction(function () use ($tramite, $data, $autor): Tramite {
            if ($autor !== null) {
                $data['updated_by'] = $autor->id;
            }

            return $this->repository->update($tramite, $data);
        });
    }

    public function eliminar(Tramite $tramite): bool
    {
        return $this->repository->delete($tramite);
    }
}
