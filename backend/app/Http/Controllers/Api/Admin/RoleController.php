<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;

final class RoleController extends Controller implements HasMiddleware
{
    /** @return list<\Illuminate\Routing\Controllers\Middleware|string> */
    public static function middleware(): array
    {
        return [
            'auth:api',
            new Middleware('permission:panel.access'),
            new Middleware('permission:roles.view', only: ['index', 'show']),
            new Middleware('permission:roles.create', only: ['store']),
            new Middleware('permission:roles.update', only: ['update']),
            new Middleware('permission:roles.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = Role::query()->where('guard_name', 'api')->with('permissions');
        if (! empty($params['q'])) {
            $query->where('name', 'like', '%' . $params['q'] . '%');
        }

        return RoleResource::collection(
            $query->orderBy('name')->paginate($params['per_page'] ?? 50)->withQueryString()
        )->additional(['message' => 'Listado obtenido correctamente.']);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $perms = $data['permissions'] ?? [];

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'api']);
        if ($perms !== []) {
            $role->syncPermissions($perms);
        }

        return response()->json([
            'message' => 'Rol creado exitosamente.',
            'data' => new RoleResource($role->fresh('permissions')),
        ], 201);
    }

    public function show(Role $role): JsonResponse
    {
        $role->load('permissions');

        return response()->json([
            'message' => 'Detalle obtenido correctamente.',
            'data' => new RoleResource($role),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $data = $request->validated();

        if (in_array($role->name, ['admin'], true) && isset($data['name']) && $data['name'] !== 'admin') {
            return response()->json(['message' => 'No se puede renombrar el rol admin.'], 409);
        }

        if (! empty($data['name'])) {
            $role->name = $data['name'];
            $role->save();
        }

        if (array_key_exists('permissions', $data)) {
            $role->syncPermissions($data['permissions']);
        }

        return response()->json([
            'message' => 'Rol actualizado correctamente.',
            'data' => new RoleResource($role->fresh('permissions')),
        ]);
    }

    public function destroy(Role $role): JsonResponse
    {
        if (in_array($role->name, ['admin', 'ciudadano'], true)) {
            return response()->json(['message' => 'No se puede eliminar el rol del sistema.'], 409);
        }

        $role->delete();

        return response()->json(['message' => 'Rol eliminado correctamente.']);
    }
}
