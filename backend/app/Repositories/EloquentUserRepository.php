<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly User $model)
    {
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->newQuery()->where('email', $email)->first();
    }

    public function create(array $data): User
    {
        /** @var User $user */
        $user = $this->model->newQuery()->create($data);
        return $user;
    }
}
