<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Permisos granulares por sección del panel administrativo.
 * Convención: <modulo>.<accion>.
 */
final class RolesPermissionsSeeder extends Seeder
{
    /** @var list<string> */
    private array $modulos = [
        'servidores', 'dependencias', 'usuarios', 'roles', 'permisos', 'geografia', 'reportes',
    ];

    /** @var list<string> */
    private array $acciones = ['view', 'create', 'update', 'delete'];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($this->generarPermisosCrud() as $name) {
            Permission::findOrCreate($name, 'api');
        }

        foreach (['servidores.publish', 'reportes.export', 'panel.access'] as $name) {
            Permission::findOrCreate($name, 'api');
        }

        $admin = Role::findOrCreate('admin', 'api');
        $admin->syncPermissions(Permission::query()->where('guard_name', 'api')->get());

        $editor = Role::findOrCreate('editor_rrhh', 'api');
        $editor->syncPermissions([
            'panel.access',
            'servidores.view', 'servidores.create', 'servidores.update', 'servidores.publish',
            'dependencias.view',
            'geografia.view',
            'reportes.view', 'reportes.export',
        ]);

        $auditor = Role::findOrCreate('auditor', 'api');
        $auditor->syncPermissions([
            'panel.access',
            'servidores.view', 'dependencias.view', 'reportes.view', 'reportes.export',
        ]);

        Role::findOrCreate('ciudadano', 'api');

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /** @return list<string> */
    private function generarPermisosCrud(): array
    {
        $permisos = [];
        foreach ($this->modulos as $modulo) {
            foreach ($this->acciones as $accion) {
                $permisos[] = "{$modulo}.{$accion}";
            }
        }
        return $permisos;
    }
}
