<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Noticia;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NoticiaRepositoryInterface
{
    /** @param  array{q?: string|null, categoria?: string|null, publicado?: bool|null, per_page?: int|null}  $filters */
    public function paginate(array $filters): LengthAwarePaginator;

    public function findBySlugPublicada(string $slug): ?Noticia;

    public function find(int $id): ?Noticia;

    /** @param  array<string, mixed>  $data */
    public function create(array $data): Noticia;

    /** @param  array<string, mixed>  $data */
    public function update(Noticia $noticia, array $data): Noticia;

    public function delete(Noticia $noticia): bool;
}
