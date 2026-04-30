<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;

/**
 * Catálogo de permisos disponibles (solo lectura).
 * Los permisos se crean por seeder, no en runtime.
 */
final class PermissionController extends Controller implements HasMiddleware
{
    /** @return list<\Illuminate\Routing\Controllers\Middleware|string> */
    public static function middleware(): array
    {
        return [
            'auth:api',
            new Middleware('permission:panel.access'),
            new Middleware('permission:permisos.view'),
        ];
    }

    public function index(): AnonymousResourceCollection
    {
        $permisos = Permission::query()->where('guard_name', 'api')->orderBy('name')->get();

        return PermissionResource::collection($permisos)
            ->additional(['message' => 'Permisos obtenidos correctamente.']);
    }
}
