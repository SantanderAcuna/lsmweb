<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Pqrsd;
use App\Models\User;
use App\Repositories\Contracts\PqrsdRepositoryInterface;
use Illuminate\Support\Carbon;

/**
 * Reglas:
 *  - Plazo legal de respuesta: 15 días hábiles para PETICION (Ley 1755/2015 Art. 14).
 *  - DENUNCIA: 15 días.
 *  - INFORMACION: 10 días hábiles (Ley 1712/2014).
 */
final class PqrsdService implements PqrsdServiceInterface
{
    public function __construct(private readonly PqrsdRepositoryInterface $repository)
    {
    }

    public function radicar(array $data): Pqrsd
    {
        $data['radicado'] = $this->generarRadicado();
        $data['fecha_limite_respuesta'] = $this->calcularFechaLimite($data['tipo']);
        $data['estado'] = 'RECIBIDA';

        return $this->repository->create($data);
    }

    public function asignar(Pqrsd $pqrsd, int $usuarioId, int $dependenciaId): Pqrsd
    {
        return $this->repository->update($pqrsd, [
            'asignado_a' => $usuarioId,
            'dependencia_asignada_id' => $dependenciaId,
            'estado' => 'EN_TRAMITE',
        ]);
    }

    public function responder(Pqrsd $pqrsd, string $respuesta, User $autor): Pqrsd
    {
        return $this->repository->update($pqrsd, [
            'respuesta' => $respuesta,
            'respondida_en' => now(),
            'estado' => 'RESPONDIDA',
            'asignado_a' => $autor->id,
        ]);
    }

    public function cambiarEstado(Pqrsd $pqrsd, string $estado): Pqrsd
    {
        return $this->repository->update($pqrsd, ['estado' => $estado]);
    }

    private function generarRadicado(): string
    {
        return 'PQRSD-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    private function calcularFechaLimite(string $tipo): Carbon
    {
        $diasHabiles = match ($tipo) {
            'INFORMACION' => 10,
            'QUEJA', 'RECLAMO', 'SUGERENCIA' => 15,
            default => 15,
        };

        $fecha = now();
        $contados = 0;
        while ($contados < $diasHabiles) {
            $fecha->addDay();
            if (! $fecha->isWeekend()) {
                $contados++;
            }
        }
        return $fecha;
    }
}
