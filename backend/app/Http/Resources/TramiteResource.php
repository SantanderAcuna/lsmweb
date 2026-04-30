<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Tramite */
class TramiteResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'descripcion' => $this->descripcion,
            'requisitos' => $this->requisitos,
            'documentos_requeridos' => $this->documentos_requeridos,
            'tiempo_estimado' => $this->tiempo_estimado,
            'costo' => $this->costo,
            'costo_variable' => $this->costo_variable,
            'categoria' => $this->categoria,
            'canal_atencion' => $this->canal_atencion,
            'url_govco' => $this->url_govco,
            'publicado' => $this->publicado,
            'dependencia' => new DependenciaResource($this->whenLoaded('dependencia')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
