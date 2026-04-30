<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Sede */
class SedeResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        $municipio = $this->whenLoaded('municipio');
        $departamento = $this->municipio?->departamento;
        $pais = $departamento?->pais;

        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'municipio_id' => $this->municipio_id,
            'municipio' => is_object($municipio) ? $municipio->nombre ?? null : null,
            'departamento' => $departamento?->nombre,
            'pais' => $pais?->nombre,
            'direccion' => $this->direccion,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'telefono' => $this->telefono,
            'correo' => $this->correo,
            'horario_atencion' => $this->horario_atencion,
            'activo' => $this->activo,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
