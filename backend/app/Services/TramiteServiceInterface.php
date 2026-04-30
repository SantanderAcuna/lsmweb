<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tramite;
use App\Models\User;

interface TramiteServiceInterface
{
    /** @param  array<string, mixed>  $data */
    public function crear(array $data, ?User $autor = null): Tramite;

    /** @param  array<string, mixed>  $data */
    public function actualizar(Tramite $tramite, array $data, ?User $autor = null): Tramite;

    public function eliminar(Tramite $tramite): bool;
}
