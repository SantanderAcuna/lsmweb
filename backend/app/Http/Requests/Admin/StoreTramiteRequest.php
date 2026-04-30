<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTramiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('tramites.create') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'codigo' => ['required', 'string', 'max:30', Rule::unique('tramites', 'codigo')->whereNull('deleted_at')],
            'nombre' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:220', 'regex:/^[a-z0-9-]+$/', Rule::unique('tramites', 'slug')->whereNull('deleted_at')],
            'descripcion' => ['required', 'string'],
            'requisitos' => ['required', 'array', 'min:1'],
            'requisitos.*' => ['string'],
            'documentos_requeridos' => ['nullable', 'array'],
            'documentos_requeridos.*' => ['string'],
            'tiempo_estimado' => ['nullable', 'string', 'max:100'],
            'costo' => ['nullable', 'numeric', 'min:0'],
            'costo_variable' => ['sometimes', 'boolean'],
            'categoria' => ['nullable', 'string', 'max:80'],
            'dependencia_id' => ['required', 'integer', Rule::exists('dependencias', 'id')->where('activo', true)],
            'canal_atencion' => ['nullable', 'string', 'max:100'],
            'url_govco' => ['nullable', 'url', 'max:255'],
            'publicado' => ['sometimes', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'slug.regex' => 'El slug solo puede contener letras minúsculas, números y guiones.',
            'codigo.unique' => 'Este código de trámite ya existe.',
            'requisitos.min' => 'Debe registrar al menos un requisito.',
        ];
    }
}
