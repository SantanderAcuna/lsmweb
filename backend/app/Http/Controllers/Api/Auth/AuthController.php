<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\Auth\UserResource;
use App\Services\Auth\AuthServiceInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * Auth API.
 *
 *  POST /api/v1/auth/register
 *  POST /api/v1/auth/login
 *  POST /api/v1/auth/logout
 *  GET  /api/v1/auth/me
 *  POST /api/v1/auth/forgot-password
 *  POST /api/v1/auth/reset-password
 */
final class AuthController extends Controller implements HasMiddleware
{
    public function __construct(private readonly AuthServiceInterface $authService)
    {
    }

    /** @return list<\Illuminate\Routing\Controllers\Middleware|string> */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', only: ['logout', 'me']),
        ];
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'Usuario registrado exitosamente.',
            'data' => [
                'user' => new UserResource($data['user']),
                'token' => $data['token'],
                'token_type' => 'Bearer',
                'expires_at' => $data['expires_at'],
            ],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->authService->login(
                (string) $request->input('email'),
                (string) $request->input('password'),
                (string) ($request->userAgent() ?? 'web')
            );
        } catch (AuthenticationException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'data' => [
                'user' => new UserResource($data['user']),
                'token' => $data['token'],
                'token_type' => 'Bearer',
                'expires_at' => $data['expires_at'],
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new UserResource($request->user()->load(['roles', 'permissions'])),
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->sendResetLink((string) $request->input('email'));

        return response()->json([
            'message' => 'Si el correo existe, se enviará un enlace de restablecimiento.',
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $ok = $this->authService->resetPassword(
            (string) $request->input('email'),
            (string) $request->input('token'),
            (string) $request->input('password'),
        );

        return response()->json([
            'message' => $ok
                ? 'Contraseña restablecida correctamente.'
                : 'No fue posible restablecer la contraseña.',
        ], $ok ? 200 : 422);
    }
}
