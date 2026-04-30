<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNoticiaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('noticias.create') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:220', 'regex:/^[a-z0-9-]+$/',
                Rule::unique('noticias', 'slug')->whereNull('deleted_at')],
            'resumen' => ['required', 'string', 'max:500'],
            'contenido' => ['required', 'string'],
            'imagen_destacada_url' => ['nullable', 'url', 'max:255'],
            'imagen_destacada_alt' => ['nullable', 'string', 'max:255', 'required_with:imagen_destacada_url'],
            'categoria' => ['required', 'string', 'max:80'],
            'etiquetas' => ['nullable', 'array'],
            'etiquetas.*' => ['string', 'max:60'],
            'publicado' => ['sometimes', 'boolean'],
            'publicado_en' => ['nullable', 'date'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'imagen_destacada_alt.required_with' => 'El texto alternativo (alt) es obligatorio cuando se proporciona imagen (WCAG 2.1 — Resolución 1519/2020).',
            'slug.regex' => 'El slug solo puede contener letras minúsculas, números y guiones.',
        ];
    }
}
