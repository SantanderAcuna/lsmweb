<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Tramite;
use App\Repositories\Contracts\TramiteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentTramiteRepository implements TramiteRepositoryInterface
{
    public function __construct(private readonly Tramite $model)
    {
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('dependencia');

        if (! empty($filters['q'])) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $filters['q']) . '%';
            $query->where(fn ($q) => $q->where('nombre', 'like', $like)->orWhere('descripcion', 'like', $like));
        }
        if (! empty($filters['categoria'])) {
            $query->where('categoria', $filters['categoria']);
        }
        if (! empty($filters['dependencia_id'])) {
            $query->where('dependencia_id', $filters['dependencia_id']);
        }
        if (array_key_exists('publicado', $filters) && $filters['publicado'] !== null) {
            $query->where('publicado', $filters['publicado']);
        }

        $perPage = min(max((int) ($filters['per_page'] ?? 15), 1), 100);

        return $query->orderBy('nombre')->paginate($perPage)->withQueryString();
    }

    public function findBySlugPublicado(string $slug): ?Tramite
    {
        return $this->model->newQuery()
            ->publicados()
            ->with('dependencia')
            ->where('slug', $slug)
            ->first();
    }

    public function find(int $id): ?Tramite
    {
        return $this->model->newQuery()->with('dependencia')->find($id);
    }

    public function create(array $data): Tramite
    {
        /** @var Tramite $t */
        $t = $this->model->newQuery()->create($data);
        return $t->fresh('dependencia');
    }

    public function update(Tramite $tramite, array $data): Tramite
    {
        $tramite->fill($data)->save();
        return $tramite->fresh('dependencia');
    }

    public function delete(Tramite $tramite): bool
    {
        return (bool) $tramite->delete();
    }
}
