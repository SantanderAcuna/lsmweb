<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ServidorPublico;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ServidorPublico */
class ServidorPublicoResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        $municipio = $this->municipioNacimiento;
        $departamento = $municipio?->departamento;
        $pais = $departamento?->pais;

        return [
            'id' => $this->id,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'nombre_completo' => $this->nombre_completo,
            'pais_nacimiento' => $pais?->nombre,
            'depto_nacimiento' => $departamento?->nombre,
            'ciudad_nacimiento' => $municipio?->nombre,
            'fecha_nacimiento' => $this->fecha_nacimiento?->toDateString(),
            'genero' => $this->genero,
            'cargo' => $this->cargo,
            'naturaleza_cargo' => $this->naturaleza_cargo?->value,
            'naturaleza_cargo_label' => $this->naturaleza_cargo?->label(),
            'salario_basico' => $this->salario_basico,
            'correo_institucional' => $this->correo_institucional,
            'telefono_oficina' => $this->telefono_oficina,
            'extension' => $this->extension,
            'sigep_url' => $this->sigep_url,
            'foto_url' => $this->foto_url,
            'publicado' => $this->publicado,
            'dependencia' => new DependenciaResource($this->whenLoaded('dependencia')),
            'formaciones_academicas' => FormacionAcademicaResource::collection(
                $this->whenLoaded('formacionesAcademicas')
            ),
            'experiencias_profesionales' => ExperienciaProfesionalResource::collection(
                $this->whenLoaded('experienciasProfesionales')
            ),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
