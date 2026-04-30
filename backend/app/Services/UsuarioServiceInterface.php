<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

interface UsuarioServiceInterface
{
    /** @param  array<string, mixed>  $data */
    public function crear(array $data, ?User $autor = null): User;

    /** @param  array<string, mixed>  $data */
    public function actualizar(User $user, array $data, ?User $autor = null): User;

    public function eliminar(User $user): bool;

    /** @param  array<int, string>  $roles */
    public function sincronizarRoles(User $user, array $roles): User;
}
