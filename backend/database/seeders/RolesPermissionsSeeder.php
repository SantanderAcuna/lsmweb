<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Permisos granulares por sección del panel administrativo.
 * Convención: <modulo>.<accion>
 */
final class RolesPermissionsSeeder extends Seeder
{
    /** @var list<string> */
    private array $modulos = [
        'servidores', 'dependencias', 'usuarios', 'roles', 'permisos',
        'geografia', 'reportes', 'tramites', 'pqrsd', 'noticias', 'sedes',
    ];

    /** @var list<string> */
    private array $acciones = ['view', 'create', 'update', 'delete'];

    /** @var list<string> */
    private array $extras = [
        'servidores.publish',
        'noticias.publish',
        'tramites.publish',
        'pqrsd.assign',
        'pqrsd.respond',
        'reportes.export',
        'panel.access',
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($this->generarPermisosCrud() as $name) {
            Permission::findOrCreate($name, 'api');
        }

        foreach ($this->extras as $name) {
            Permission::findOrCreate($name, 'api');
        }

        $admin = Role::findOrCreate('admin', 'api');
        $admin->syncPermissions(Permission::query()->where('guard_name', 'api')->get());

        $editorRrhh = Role::findOrCreate('editor_rrhh', 'api');
        $editorRrhh->syncPermissions([
            'panel.access',
            'servidores.view', 'servidores.create', 'servidores.update', 'servidores.publish',
            'dependencias.view',
            'geografia.view',
            'reportes.view', 'reportes.export',
        ]);

        $editorComunicaciones = Role::findOrCreate('editor_comunicaciones', 'api');
        $editorComunicaciones->syncPermissions([
            'panel.access',
            'noticias.view', 'noticias.create', 'noticias.update', 'noticias.delete', 'noticias.publish',
            'tramites.view',
        ]);

        $editorServicios = Role::findOrCreate('editor_servicios', 'api');
        $editorServicios->syncPermissions([
            'panel.access',
            'tramites.view', 'tramites.create', 'tramites.update', 'tramites.delete', 'tramites.publish',
            'sedes.view', 'sedes.create', 'sedes.update', 'sedes.delete',
            'dependencias.view',
        ]);

        $oficialPqrsd = Role::findOrCreate('oficial_pqrsd', 'api');
        $oficialPqrsd->syncPermissions([
            'panel.access',
            'pqrsd.view', 'pqrsd.update', 'pqrsd.assign', 'pqrsd.respond',
            'dependencias.view',
        ]);

        $auditor = Role::findOrCreate('auditor', 'api');
        $auditor->syncPermissions([
            'panel.access',
            'servidores.view', 'dependencias.view', 'tramites.view',
            'noticias.view', 'pqrsd.view',
            'reportes.view', 'reportes.export',
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
