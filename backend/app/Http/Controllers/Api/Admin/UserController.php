<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\Auth\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\UsuarioServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class UserController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly UsuarioServiceInterface $service,
    ) {
    }

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

        return UserResource::collection($this->repository->paginate($params))
            ->additional(['message' => 'Listado obtenido correctamente.']);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->service->crear($request->validated(), $request->user());

        return response()->json([
            'message' => 'Usuario creado exitosamente.',
            'data' => new UserResource($user),
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
        $user = $this->service->actualizar($user, $request->validated(), $request->user());

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'data' => new UserResource($user),
        ]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'No puede eliminarse a sí mismo.'], 409);
        }

        $this->service->eliminar($user);

        return response()->json(['message' => 'Usuario eliminado correctamente.']);
    }
}
