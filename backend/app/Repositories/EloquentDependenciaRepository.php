<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Dependencia;
use App\Repositories\Contracts\DependenciaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class EloquentDependenciaRepository implements DependenciaRepositoryInterface
{
    public function __construct(private readonly Dependencia $model)
    {
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('padre');

        if (! empty($filters['q'])) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $filters['q']) . '%';
            $query->where(fn ($q) => $q->where('nombre', 'like', $like)->orWhere('codigo', 'like', $like));
        }
        if (array_key_exists('activo', $filters) && $filters['activo'] !== null) {
            $query->where('activo', $filters['activo']);
        }
        if (! empty($filters['padre_id'])) {
            $query->where('dependencia_padre_id', $filters['padre_id']);
        }

        $perPage = min(max((int) ($filters['per_page'] ?? 50), 1), 200);

        return $query->orderBy('nombre')->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Dependencia
    {
        return $this->model->newQuery()->with(['padre', 'hijos'])->find($id);
    }

    public function arbolActivo(): Collection
    {
        return $this->model->newQuery()
            ->activas()
            ->with(['hijos' => fn ($q) => $q->activas()])
            ->whereNull('dependencia_padre_id')
            ->orderBy('nombre')
            ->get();
    }

    public function create(array $data): Dependencia
    {
        /** @var Dependencia $d */
        $d = $this->model->newQuery()->create($data);
        return $d->fresh('padre');
    }

    public function update(Dependencia $dependencia, array $data): Dependencia
    {
        $dependencia->fill($data)->save();
        return $dependencia->fresh('padre');
    }

    public function delete(Dependencia $dependencia): bool
    {
        return (bool) $dependencia->delete();
    }
}
