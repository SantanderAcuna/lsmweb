<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Pqrsd;
use App\Repositories\Contracts\PqrsdRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentPqrsdRepository implements PqrsdRepositoryInterface
{
    public function __construct(private readonly Pqrsd $model)
    {
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['dependenciaAsignada', 'asignadoA']);

        if (! empty($filters['tipo'])) {
            $query->where('tipo', $filters['tipo']);
        }
        if (! empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }
        if (! empty($filters['q'])) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $filters['q']) . '%';
            $query->where(fn ($q) => $q->where('asunto', 'like', $like)->orWhere('radicado', 'like', $like));
        }

        $perPage = min(max((int) ($filters['per_page'] ?? 15), 1), 100);

        return $query->orderByDesc('created_at')->paginate($perPage)->withQueryString();
    }

    public function findByRadicado(string $radicado): ?Pqrsd
    {
        return $this->model->newQuery()
            ->with(['dependenciaAsignada', 'asignadoA'])
            ->where('radicado', $radicado)
            ->first();
    }

    public function create(array $data): Pqrsd
    {
        /** @var Pqrsd $p */
        $p = $this->model->newQuery()->create($data);
        return $p->fresh(['dependenciaAsignada', 'asignadoA']);
    }

    public function update(Pqrsd $pqrsd, array $data): Pqrsd
    {
        $pqrsd->fill($data)->save();
        return $pqrsd->fresh(['dependenciaAsignada', 'asignadoA']);
    }
}
