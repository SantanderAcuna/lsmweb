<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Pqrsd;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PqrsdRepositoryInterface
{
    /** @param  array{tipo?: string|null, estado?: string|null, q?: string|null, per_page?: int|null}  $filters */
    public function paginate(array $filters): LengthAwarePaginator;

    public function findByRadicado(string $radicado): ?Pqrsd;

    /** @param  array<string, mixed>  $data */
    public function create(array $data): Pqrsd;

    /** @param  array<string, mixed>  $data */
    public function update(Pqrsd $pqrsd, array $data): Pqrsd;
}
