<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Dependencia;
use App\Models\User;

final class DependenciaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('dependencias.view');
    }

    public function view(User $user, Dependencia $dependencia): bool
    {
        return $user->can('dependencias.view');
    }

    public function create(User $user): bool
    {
        return $user->can('dependencias.create');
    }

    public function update(User $user, Dependencia $dependencia): bool
    {
        return $user->can('dependencias.update');
    }

    public function delete(User $user, Dependencia $dependencia): bool
    {
        return $user->can('dependencias.delete');
    }
}
