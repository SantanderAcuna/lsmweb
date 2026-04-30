<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Noticia;
use App\Repositories\Contracts\NoticiaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentNoticiaRepository implements NoticiaRepositoryInterface
{
    public function __construct(private readonly Noticia $model)
    {
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['autor']);

        if (! empty($filters['q'])) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $filters['q']) . '%';
            $query->where(fn ($q) => $q->where('titulo', 'like', $like)->orWhere('resumen', 'like', $like));
        }
        if (! empty($filters['categoria'])) {
            $query->where('categoria', $filters['categoria']);
        }
        if (array_key_exists('publicado', $filters) && $filters['publicado'] !== null) {
            $query->where('publicado', $filters['publicado']);
        }

        $perPage = min(max((int) ($filters['per_page'] ?? 10), 1), 100);

        return $query->orderByDesc('publicado_en')->paginate($perPage)->withQueryString();
    }

    public function findBySlugPublicada(string $slug): ?Noticia
    {
        return $this->model->newQuery()
            ->publicadas()
            ->with('autor')
            ->where('slug', $slug)
            ->first();
    }

    public function find(int $id): ?Noticia
    {
        return $this->model->newQuery()->with('autor')->find($id);
    }

    public function create(array $data): Noticia
    {
        /** @var Noticia $n */
        $n = $this->model->newQuery()->create($data);
        return $n->fresh('autor');
    }

    public function update(Noticia $noticia, array $data): Noticia
    {
        $noticia->fill($data)->save();
        return $noticia->fresh('autor');
    }

    public function delete(Noticia $noticia): bool
    {
        return (bool) $noticia->delete();
    }
}
