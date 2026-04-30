<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ExperienciaProfesional;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ExperienciaProfesional */
class ExperienciaProfesionalResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'empresa' => $this->empresa,
            'cargo' => $this->cargo,
            'sector' => $this->sector?->value,
            'fecha_inicio' => $this->fecha_inicio?->toDateString(),
            'fecha_fin' => $this->fecha_fin?->toDateString(),
            'funciones' => $this->funciones,
        ];
    }
}
