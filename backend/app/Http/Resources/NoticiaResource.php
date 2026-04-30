<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Noticia */
class NoticiaResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'slug' => $this->slug,
            'resumen' => $this->resumen,
            'contenido' => $this->contenido,
            'imagen_destacada_url' => $this->imagen_destacada_url,
            'imagen_destacada_alt' => $this->imagen_destacada_alt,
            'categoria' => $this->categoria,
            'etiquetas' => $this->etiquetas,
            'publicado' => $this->publicado,
            'publicado_en' => $this->publicado_en?->toIso8601String(),
            'autor' => $this->whenLoaded('autor', fn () => [
                'id' => $this->autor->id,
                'name' => $this->autor->name,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
