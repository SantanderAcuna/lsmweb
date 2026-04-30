<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDependenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('dependencias.create') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'codigo' => ['required', 'string', 'max:20', Rule::unique('dependencias', 'codigo')],
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string'],
            'dependencia_padre_id' => ['nullable', 'integer', Rule::exists('dependencias', 'id')],
            'extension' => ['nullable', 'string', 'max:20'],
            'correo' => ['nullable', 'email:rfc', 'max:150', 'ends_with:@santamarta.gov.co'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }
}
