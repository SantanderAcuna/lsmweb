<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\FormacionAcademica;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin FormacionAcademica */
class FormacionAcademicaResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nivel' => $this->nivel?->value,
            'titulo' => $this->titulo,
            'institucion' => $this->institucion,
            'pais_id' => $this->pais_id,
            'pais' => $this->pais?->nombre,
            'ano_grado' => $this->ano_grado,
        ];
    }
}
