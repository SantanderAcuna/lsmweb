<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Sede;
use App\Models\User;

final class SedePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('sedes.view');
    }

    public function view(User $user, Sede $sede): bool
    {
        return $user->can('sedes.view');
    }

    public function create(User $user): bool
    {
        return $user->can('sedes.create');
    }

    public function update(User $user, Sede $sede): bool
    {
        return $user->can('sedes.update');
    }

    public function delete(User $user, Sede $sede): bool
    {
        return $user->can('sedes.delete');
    }
}
