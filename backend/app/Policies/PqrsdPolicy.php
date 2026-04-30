<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Pqrsd;
use App\Models\User;

final class PqrsdPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('pqrsd.view');
    }

    public function view(User $user, Pqrsd $pqrsd): bool
    {
        return $user->can('pqrsd.view');
    }

    public function update(User $user, Pqrsd $pqrsd): bool
    {
        return $user->can('pqrsd.update');
    }

    public function assign(User $user, Pqrsd $pqrsd): bool
    {
        return $user->can('pqrsd.assign');
    }

    public function respond(User $user, Pqrsd $pqrsd): bool
    {
        return $user->can('pqrsd.respond');
    }

    public function delete(User $user, Pqrsd $pqrsd): bool
    {
        return $user->can('pqrsd.delete');
    }
}
