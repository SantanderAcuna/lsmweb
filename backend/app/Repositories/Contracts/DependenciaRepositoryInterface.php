<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Dependencia;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface DependenciaRepositoryInterface
{
    /** @param  array{q?: string|null, activo?: bool|null, padre_id?: int|null, per_page?: int|null}  $filters */
    public function paginate(array $filters): LengthAwarePaginator;

    public function find(int $id): ?Dependencia;

    public function arbolActivo(): Collection;

    /** @param  array<string, mixed>  $data */
    public function create(array $data): Dependencia;

    /** @param  array<string, mixed>  $data */
    public function update(Dependencia $dependencia, array $data): Dependencia;

    public function delete(Dependencia $dependencia): bool;
}
