<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

final class RoleService implements RoleServiceInterface
{
    public function crear(array $data, ?User $autor = null): Role
    {
        return DB::transaction(function () use ($data): Role {
            $perms = $data['permissions'] ?? [];

            $role = Role::create([
                'name' => $data['name'],
                'guard_name' => 'api',
            ]);

            if (is_array($perms) && $perms !== []) {
                $role->syncPermissions($perms);
            }

            return $role->fresh('permissions');
        });
    }

    public function actualizar(Role $role, array $data, ?User $autor = null): Role
    {
        return DB::transaction(function () use ($role, $data): Role {
            if (! empty($data['name'])) {
                $role->name = $data['name'];
                $role->save();
            }

            if (array_key_exists('permissions', $data)) {
                $role->syncPermissions($data['permissions'] ?? []);
            }

            return $role->fresh('permissions');
        });
    }

    public function eliminar(Role $role): bool
    {
        return (bool) $role->delete();
    }

    public function sincronizarPermisos(Role $role, array $permisos): Role
    {
        $role->syncPermissions($permisos);

        return $role->fresh('permissions');
    }
}
