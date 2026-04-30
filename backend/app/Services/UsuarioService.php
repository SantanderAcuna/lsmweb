<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class UsuarioService implements UsuarioServiceInterface
{
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    public function crear(array $data, ?User $autor = null): User
    {
        return DB::transaction(function () use ($data): User {
            $roles = $data['roles'] ?? [];
            unset($data['roles']);

            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user = $this->repository->create($data);

            if (is_array($roles) && $roles !== []) {
                $user->syncRoles($roles);
            }

            return $user->fresh(['roles', 'permissions']);
        });
    }

    public function actualizar(User $user, array $data, ?User $autor = null): User
    {
        return DB::transaction(function () use ($user, $data): User {
            $roles = array_key_exists('roles', $data) ? $data['roles'] : null;
            unset($data['roles']);

            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user = $this->repository->update($user, $data);

            if (is_array($roles)) {
                $user->syncRoles($roles);
            }

            return $user->fresh(['roles', 'permissions']);
        });
    }

    public function eliminar(User $user): bool
    {
        return $this->repository->delete($user);
    }

    public function sincronizarRoles(User $user, array $roles): User
    {
        $user->syncRoles($roles);

        return $user->fresh(['roles', 'permissions']);
    }
}
