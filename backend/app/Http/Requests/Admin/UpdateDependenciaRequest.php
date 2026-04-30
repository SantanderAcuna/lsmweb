<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Dependencia;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDependenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('dependencia') instanceof Dependencia
            && ($this->user()?->can('dependencias.update') ?? false);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        /** @var Dependencia $dependencia */
        $dependencia = $this->route('dependencia');

        return [
            'codigo' => ['sometimes', 'required', 'string', 'max:20',
                Rule::unique('dependencias', 'codigo')->ignore($dependencia->id)],
            'nombre' => ['sometimes', 'required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string'],
            'dependencia_padre_id' => ['nullable', 'integer',
                Rule::exists('dependencias', 'id'),
                Rule::notIn([$dependencia->id]),
            ],
            'extension' => ['nullable', 'string', 'max:20'],
            'correo' => ['nullable', 'email:rfc', 'max:150', 'ends_with:@santamarta.gov.co'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'dependencia_padre_id.not_in' => 'Una dependencia no puede ser su propio padre.',
        ];
    }
}
