<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Tramite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TramiteRepositoryInterface
{
    /** @param  array{q?: string|null, categoria?: string|null, dependencia_id?: int|null, publicado?: bool|null, per_page?: int|null}  $filters */
    public function paginate(array $filters): LengthAwarePaginator;

    public function findBySlugPublicado(string $slug): ?Tramite;

    public function find(int $id): ?Tramite;

    /** @param  array<string, mixed>  $data */
    public function create(array $data): Tramite;

    /** @param  array<string, mixed>  $data */
    public function update(Tramite $tramite, array $data): Tramite;

    public function delete(Tramite $tramite): bool;
}
