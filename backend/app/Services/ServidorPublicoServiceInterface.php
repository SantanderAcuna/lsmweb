<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ServidorPublico;
use App\Models\User;

interface ServidorPublicoServiceInterface
{
    /** @param  array<string, mixed>  $data */
    public function crear(array $data, User $autor): ServidorPublico;

    /** @param  array<string, mixed>  $data */
    public function actualizar(ServidorPublico $servidor, array $data, User $autor): ServidorPublico;

    public function eliminar(ServidorPublico $servidor): bool;
}
