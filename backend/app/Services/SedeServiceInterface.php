<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Sede;
use App\Models\User;

interface SedeServiceInterface
{
    /** @param  array<string, mixed>  $data */
    public function crear(array $data, ?User $autor = null): Sede;

    /** @param  array<string, mixed>  $data */
    public function actualizar(Sede $sede, array $data, ?User $autor = null): Sede;

    public function eliminar(Sede $sede): bool;
}
