<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Dependencia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Dependencia */
class DependenciaResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'extension' => $this->extension,
            'correo' => $this->correo,
            'activo' => $this->activo,
            'dependencia_padre_id' => $this->dependencia_padre_id,
        ];
    }
}
