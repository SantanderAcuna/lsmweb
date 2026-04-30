<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Tramite;
use App\Models\User;

final class TramitePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('tramites.view');
    }

    public function view(User $user, Tramite $tramite): bool
    {
        return $user->can('tramites.view');
    }

    public function create(User $user): bool
    {
        return $user->can('tramites.create');
    }

    public function update(User $user, Tramite $tramite): bool
    {
        return $user->can('tramites.update');
    }

    public function delete(User $user, Tramite $tramite): bool
    {
        return $user->can('tramites.delete');
    }

    public function publish(User $user, Tramite $tramite): bool
    {
        return $user->can('tramites.publish');
    }
}
