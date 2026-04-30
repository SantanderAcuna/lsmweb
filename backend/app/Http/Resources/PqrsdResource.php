<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Pqrsd;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Pqrsd */
class PqrsdResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'radicado' => $this->radicado,
            'tipo' => $this->tipo,
            'asunto' => $this->asunto,
            'descripcion' => $this->descripcion,
            'anonima' => $this->anonima,
            'solicitante_nombre' => $this->when(
                ! $this->anonima && $request->user()?->can('pqrsd.view'),
                fn () => $this->solicitante_nombre,
            ),
            'estado' => $this->estado,
            'fecha_limite_respuesta' => $this->fecha_limite_respuesta?->toDateString(),
            'respondida_en' => $this->respondida_en?->toIso8601String(),
            'respuesta' => $this->when($this->estado === 'RESPONDIDA' || $request->user()?->can('pqrsd.view'),
                fn () => $this->respuesta),
            'dependencia_asignada' => new DependenciaResource($this->whenLoaded('dependenciaAsignada')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
