<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ServidorPublico;
use App\Repositories\Contracts\ServidorPublicoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class EloquentServidorPublicoRepository implements ServidorPublicoRepositoryInterface
{
    /** @var list<string> */
    private array $relations = [
        'dependencia',
        'municipioNacimiento.departamento.pais',
        'formacionesAcademicas.pais',
        'experienciasProfesionales',
    ];

    public function __construct(private readonly ServidorPublico $model)
    {
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with($this->relations)
            ->buscar($filters['q'] ?? null);

        if (! empty($filters['dependencia_id'])) {
            $query->where('dependencia_id', $filters['dependencia_id']);
        }

        if (array_key_exists('publicado', $filters) && $filters['publicado'] !== null) {
            $query->where('publicado', $filters['publicado']);
        }

        $perPage = (int) ($filters['per_page'] ?? 15);
        $perPage = min(max($perPage, 1), 100);

        return $query
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findPublicado(int $id): ?ServidorPublico
    {
        return $this->model->newQuery()
            ->publicados()
            ->with($this->relations)
            ->find($id);
    }

    public function find(int $id): ?ServidorPublico
    {
        return $this->model->newQuery()->with($this->relations)->find($id);
    }

    public function create(array $data): ServidorPublico
    {
        /** @var ServidorPublico $servidor */
        $servidor = $this->model->newQuery()->create($data);

        return $servidor->fresh($this->relations);
    }

    public function update(ServidorPublico $servidor, array $data): ServidorPublico
    {
        $servidor->fill($data)->save();

        return $servidor->fresh($this->relations);
    }

    public function delete(ServidorPublico $servidor): bool
    {
        return (bool) $servidor->delete();
    }

    public function allPublicadosForExport(): Collection
    {
        return $this->model->newQuery()
            ->publicados()
            ->with($this->relations)
            ->orderBy('apellidos')
            ->get();
    }
}
