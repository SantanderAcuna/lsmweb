<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Sede;
use App\Repositories\Contracts\SedeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class EloquentSedeRepository implements SedeRepositoryInterface
{
    public function __construct(private readonly Sede $model)
    {
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('municipio.departamento.pais');

        if (! empty($filters['q'])) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $filters['q']) . '%';
            $query->where(fn ($q) => $q->where('nombre', 'like', $like)->orWhere('direccion', 'like', $like));
        }
        if (! empty($filters['municipio_id'])) {
            $query->where('municipio_id', $filters['municipio_id']);
        }
        if (array_key_exists('activo', $filters) && $filters['activo'] !== null) {
            $query->where('activo', $filters['activo']);
        }

        $perPage = min(max((int) ($filters['per_page'] ?? 15), 1), 100);

        return $query->orderBy('nombre')->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Sede
    {
        return $this->model->newQuery()->with('municipio.departamento.pais')->find($id);
    }

    public function activas(): Collection
    {
        return $this->model->newQuery()->activas()->with('municipio.departamento.pais')->orderBy('nombre')->get();
    }

    public function create(array $data): Sede
    {
        /** @var Sede $sede */
        $sede = $this->model->newQuery()->create($data);
        return $sede->fresh('municipio.departamento.pais');
    }

    public function update(Sede $sede, array $data): Sede
    {
        $sede->fill($data)->save();
        return $sede->fresh('municipio.departamento.pais');
    }

    public function delete(Sede $sede): bool
    {
        return (bool) $sede->delete();
    }
}
