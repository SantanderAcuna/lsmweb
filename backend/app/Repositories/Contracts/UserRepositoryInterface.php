<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function find(int $id): ?User;

    /** @param  array{q?: string|null, estado?: string|null, per_page?: int|null}  $filters */
    public function paginate(array $filters): LengthAwarePaginator;

    /** @param  array<string, mixed>  $data */
    public function create(array $data): User;

    /** @param  array<string, mixed>  $data */
    public function update(User $user, array $data): User;

    public function delete(User $user): bool;
}
