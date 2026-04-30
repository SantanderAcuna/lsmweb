<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Dependencia;
use App\Models\User;

interface DependenciaServiceInterface
{
    /** @param  array<string, mixed>  $data */
    public function crear(array $data, ?User $autor = null): Dependencia;

    /** @param  array<string, mixed>  $data */
    public function actualizar(Dependencia $dependencia, array $data, ?User $autor = null): Dependencia;

    public function eliminar(Dependencia $dependencia): bool;
}
