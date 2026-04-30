<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('usuarios.view');
    }

    public function view(User $user, User $target): bool
    {
        return $user->id === $target->id || $user->can('usuarios.view');
    }

    public function create(User $user): bool
    {
        return $user->can('usuarios.create');
    }

    public function update(User $user, User $target): bool
    {
        return $user->id === $target->id || $user->can('usuarios.update');
    }

    public function delete(User $user, User $target): bool
    {
        return $user->can('usuarios.delete') && $user->id !== $target->id;
    }
}
