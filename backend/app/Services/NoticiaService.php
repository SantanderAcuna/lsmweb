<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Noticia;
use App\Models\User;
use App\Repositories\Contracts\NoticiaRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class NoticiaService implements NoticiaServiceInterface
{
    public function __construct(private readonly NoticiaRepositoryInterface $repository)
    {
    }

    public function crear(array $data, ?User $autor = null): Noticia
    {
        return DB::transaction(function () use ($data, $autor): Noticia {
            if ($autor !== null) {
                $data['autor_id'] = $autor->id;
                $data['actualizado_por'] = $autor->id;
            }

            return $this->repository->create($data);
        });
    }

    public function actualizar(Noticia $noticia, array $data, ?User $autor = null): Noticia
    {
        return DB::transaction(function () use ($noticia, $data, $autor): Noticia {
            if ($autor !== null) {
                $data['actualizado_por'] = $autor->id;
            }

            return $this->repository->update($noticia, $data);
        });
    }

    public function eliminar(Noticia $noticia): bool
    {
        return $this->repository->delete($noticia);
    }
}
