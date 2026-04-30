<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ServidorPublico;
use App\Models\User;
use App\Repositories\Contracts\ServidorPublicoRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Orquesta el agregado Servidor + Formaciones + Experiencias en una transacción.
 * Cumple SRP (lógica de aplicación, no de persistencia).
 */
final class ServidorPublicoService implements ServidorPublicoServiceInterface
{
    /** @var list<string> */
    private array $relacionesPostUpdate = [
        'dependencia', 'municipioNacimiento.departamento.pais',
        'formacionesAcademicas.pais', 'experienciasProfesionales',
    ];

    public function __construct(private readonly ServidorPublicoRepositoryInterface $repository)
    {
    }

    public function crear(array $data, User $autor): ServidorPublico
    {
        return DB::transaction(function () use ($data, $autor): ServidorPublico {
            $formaciones = $data['formaciones_academicas'] ?? [];
            $experiencias = $data['experiencias_profesionales'] ?? [];
            unset($data['formaciones_academicas'], $data['experiencias_profesionales']);

            $data['created_by'] = $autor->id;
            $data['updated_by'] = $autor->id;

            $servidor = $this->repository->create($data);

            if ($formaciones !== []) {
                $servidor->formacionesAcademicas()->createMany($formaciones);
            }
            if ($experiencias !== []) {
                $servidor->experienciasProfesionales()->createMany($experiencias);
            }

            return $servidor->fresh($this->relacionesPostUpdate);
        });
    }

    public function actualizar(ServidorPublico $servidor, array $data, User $autor): ServidorPublico
    {
        return DB::transaction(function () use ($servidor, $data, $autor): ServidorPublico {
            $formaciones = array_key_exists('formaciones_academicas', $data) ? $data['formaciones_academicas'] : null;
            $experiencias = array_key_exists('experiencias_profesionales', $data) ? $data['experiencias_profesionales'] : null;
            unset($data['formaciones_academicas'], $data['experiencias_profesionales']);

            $data['updated_by'] = $autor->id;

            $servidor = $this->repository->update($servidor, $data);

            if (is_array($formaciones)) {
                $servidor->formacionesAcademicas()->delete();
                if ($formaciones !== []) {
                    $servidor->formacionesAcademicas()->createMany($formaciones);
                }
            }
            if (is_array($experiencias)) {
                $servidor->experienciasProfesionales()->delete();
                if ($experiencias !== []) {
                    $servidor->experienciasProfesionales()->createMany($experiencias);
                }
            }

            return $servidor->fresh($this->relacionesPostUpdate);
        });
    }

    public function eliminar(ServidorPublico $servidor): bool
    {
        return $this->repository->delete($servidor);
    }
}
