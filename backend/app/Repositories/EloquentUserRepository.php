<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly User $model)
    {
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->newQuery()->where('email', $email)->first();
    }

    public function find(int $id): ?User
    {
        return $this->model->newQuery()->with(['roles', 'permissions'])->find($id);
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['roles', 'permissions']);

        if (! empty($filters['q'])) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $filters['q']) . '%';
            $query->where(fn ($qq) => $qq->where('name', 'like', $like)->orWhere('email', 'like', $like));
        }
        if (! empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        $perPage = min(max((int) ($filters['per_page'] ?? 15), 1), 100);

        return $query->orderBy('name')->paginate($perPage)->withQueryString();
    }

    public function create(array $data): User
    {
        /** @var User $user */
        $user = $this->model->newQuery()->create($data);
        return $user;
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data)->save();
        return $user->fresh(['roles', 'permissions']);
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }
}
