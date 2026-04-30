<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;

interface AuthServiceInterface
{
    /**
     * @param  array{name: string, email: string, password: string}  $data
     * @return array{user: User, token: string, expires_at: \DateTimeInterface}
     */
    public function register(array $data): array;

    /**
     * @return array{user: User, token: string, expires_at: \DateTimeInterface}
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function login(string $email, string $password, string $deviceName = 'web'): array;

    public function logout(User $user): void;

    public function sendResetLink(string $email): bool;

    public function resetPassword(string $email, string $token, string $newPassword): bool;
}
