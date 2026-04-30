<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Noticia;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNoticiaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $noticia = $this->route('noticia');
        return $noticia instanceof Noticia
            && ($this->user()?->can('noticias.update') ?? false);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        /** @var Noticia $noticia */
        $noticia = $this->route('noticia');

        return [
            'titulo' => ['sometimes', 'required', 'string', 'max:200'],
            'slug' => ['sometimes', 'required', 'string', 'max:220', 'regex:/^[a-z0-9-]+$/',
                Rule::unique('noticias', 'slug')->ignore($noticia->id)->whereNull('deleted_at')],
            'resumen' => ['sometimes', 'required', 'string', 'max:500'],
            'contenido' => ['sometimes', 'required', 'string'],
            'imagen_destacada_url' => ['nullable', 'url', 'max:255'],
            'imagen_destacada_alt' => ['nullable', 'string', 'max:255', 'required_with:imagen_destacada_url'],
            'categoria' => ['sometimes', 'required', 'string', 'max:80'],
            'etiquetas' => ['nullable', 'array'],
            'etiquetas.*' => ['string', 'max:60'],
            'publicado' => ['sometimes', 'boolean'],
            'publicado_en' => ['nullable', 'date'],
        ];
    }
}
