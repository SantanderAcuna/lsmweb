<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ServidorPublico;
use App\Models\User;

/**
 * Policy del módulo Servidores Públicos.
 * Encadena al sistema granular de spatie: cada permiso vive en BD.
 */
final class ServidorPublicoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('servidores.view');
    }

    public function view(User $user, ServidorPublico $servidor): bool
    {
        return $user->can('servidores.view');
    }

    public function create(User $user): bool
    {
        return $user->can('servidores.create');
    }

    public function update(User $user, ServidorPublico $servidor): bool
    {
        return $user->can('servidores.update');
    }

    public function delete(User $user, ServidorPublico $servidor): bool
    {
        return $user->can('servidores.delete');
    }

    public function publish(User $user, ServidorPublico $servidor): bool
    {
        return $user->can('servidores.publish');
    }
}
