<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\Auth\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

final class UserController extends Controller implements HasMiddleware
{
    /** @return list<\Illuminate\Routing\Controllers\Middleware|string> */
    public static function middleware(): array
    {
        return [
            'auth:api',
            new Middleware('permission:panel.access'),
            new Middleware('permission:usuarios.view', only: ['index', 'show']),
            new Middleware('permission:usuarios.create', only: ['store']),
            new Middleware('permission:usuarios.update', only: ['update']),
            new Middleware('permission:usuarios.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $q = User::query()->with(['roles', 'permissions']);

        if (! empty($params['q'])) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $params['q']) . '%';
            $q->where(fn ($qq) => $qq->where('name', 'like', $like)->orWhere('email', 'like', $like));
        }
        if (! empty($params['estado'])) {
            $q->where('estado', $params['estado']);
        }

        return UserResource::collection(
            $q->orderBy('name')->paginate($params['per_page'] ?? 15)->withQueryString()
        )->additional(['message' => 'Listado obtenido correctamente.']);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $roles = $data['roles'] ?? [];
        unset($data['roles']);
        $data['password'] = Hash::make($data['password']);

        $user = User::query()->create($data);
        if ($roles !== []) {
            $user->syncRoles($roles);
        }

        return response()->json([
            'message' => 'Usuario creado exitosamente.',
            'data' => new UserResource($user->fresh(['roles', 'permissions'])),
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        $user->load(['roles', 'permissions']);

        return response()->json([
            'message' => 'Detalle obtenido correctamente.',
            'data' => new UserResource($user),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();
        $roles = $data['roles'] ?? null;
        unset($data['roles']);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->fill($data)->save();
        if ($roles !== null) {
            $user->syncRoles($roles);
        }

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'data' => new UserResource($user->fresh(['roles', 'permissions'])),
        ]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'No puede eliminarse a sí mismo.'], 409);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente.']);
    }
}
