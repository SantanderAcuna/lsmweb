<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;

interface RoleServiceInterface
{
    /** @param  array<string, mixed>  $data */
    public function crear(array $data, ?User $autor = null): Role;

    /** @param  array<string, mixed>  $data */
    public function actualizar(Role $role, array $data, ?User $autor = null): Role;

    public function eliminar(Role $role): bool;

    /** @param  array<int, string>  $permisos */
    public function sincronizarPermisos(Role $role, array $permisos): Role;
}
