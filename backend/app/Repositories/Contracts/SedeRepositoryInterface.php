<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Sede;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SedeRepositoryInterface
{
    /** @param  array{q?: string|null, municipio_id?: int|null, activo?: bool|null, per_page?: int|null}  $filters */
    public function paginate(array $filters): LengthAwarePaginator;

    public function find(int $id): ?Sede;

    public function activas(): Collection;

    /** @param  array<string, mixed>  $data */
    public function create(array $data): Sede;

    /** @param  array<string, mixed>  $data */
    public function update(Sede $sede, array $data): Sede;

    public function delete(Sede $sede): bool;
}
