<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Pqrsd;
use App\Models\User;

interface PqrsdServiceInterface
{
    /** @param  array<string, mixed>  $data */
    public function radicar(array $data): Pqrsd;

    public function asignar(Pqrsd $pqrsd, int $usuarioId, int $dependenciaId): Pqrsd;

    public function responder(Pqrsd $pqrsd, string $respuesta, User $autor): Pqrsd;

    public function cambiarEstado(Pqrsd $pqrsd, string $estado): Pqrsd;
}
