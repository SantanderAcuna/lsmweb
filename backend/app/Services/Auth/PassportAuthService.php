<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

/**
 * Servicio de autenticación con Laravel Passport (Personal Access Tokens).
 *
 * Single Responsibility: orquesta hashing, emisión de tokens y reset.
 * Dependency Inversion: depende del UserRepositoryInterface.
 */
final class PassportAuthService implements AuthServiceInterface
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function register(array $data): array
    {
        $user = $this->users->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'estado' => 'ACTIVO',
        ]);

        $user->assignRole('ciudadano');

        return $this->emitirToken($user);
    }

    public function login(string $email, string $password, string $deviceName = 'web'): array
    {
        $user = $this->users->findByEmail($email);

        if ($user === null || ! Hash::check($password, (string) $user->password)) {
            throw new AuthenticationException('Credenciales inválidas.');
        }

        if ($user->estado !== 'ACTIVO') {
            throw new AuthenticationException('La cuenta no se encuentra activa.');
        }

        return $this->emitirToken($user, $deviceName);
    }

    public function logout(User $user): void
    {
        $user->tokens->each(static fn ($token) => $token->revoke());
    }

    public function sendResetLink(string $email): bool
    {
        return Password::sendResetLink(['email' => $email]) === Password::RESET_LINK_SENT;
    }

    public function resetPassword(string $email, string $token, string $newPassword): bool
    {
        $status = Password::reset(
            [
                'email' => $email,
                'token' => $token,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ],
            function (User $user, string $password): void {
                $user->forceFill(['password' => Hash::make($password)])->save();
                $user->tokens->each(static fn ($t) => $t->revoke());
            }
        );

        return $status === Password::PASSWORD_RESET;
    }

    /**
     * @return array{user: User, token: string, expires_at: \DateTimeInterface}
     */
    private function emitirToken(User $user, string $deviceName = 'web'): array
    {
        $result = $user->createToken($deviceName);

        return [
            'user' => $user->fresh(['roles', 'permissions']),
            'token' => $result->accessToken,
            'expires_at' => $result->token->expires_at ?? now()->addDays(15),
        ];
    }
}
