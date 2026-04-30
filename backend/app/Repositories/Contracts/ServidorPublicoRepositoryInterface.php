<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\ServidorPublico;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ServidorPublicoRepositoryInterface
{
    /**
     * @param  array{q?: string|null, dependencia_id?: int|null, publicado?: bool|null, per_page?: int|null}  $filters
     */
    public function paginate(array $filters): LengthAwarePaginator;

    public function findPublicado(int $id): ?ServidorPublico;

    public function find(int $id): ?ServidorPublico;

    /** @param  array<string, mixed>  $data */
    public function create(array $data): ServidorPublico;

    /** @param  array<string, mixed>  $data */
    public function update(ServidorPublico $servidor, array $data): ServidorPublico;

    public function delete(ServidorPublico $servidor): bool;

    public function allPublicadosForExport(): Collection;
}
