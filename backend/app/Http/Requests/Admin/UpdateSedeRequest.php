<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Sede;
use App\Rules\TelefonoColombia;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSedeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('sede') instanceof Sede
            && ($this->user()?->can('sedes.update') ?? false);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'nombre' => ['sometimes', 'required', 'string', 'max:150'],
            'municipio_id' => ['sometimes', 'required', 'integer', Rule::exists('municipios', 'id')],
            'direccion' => ['sometimes', 'required', 'string', 'max:200'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
            'telefono' => ['nullable', new TelefonoColombia()],
            'correo' => ['nullable', 'email:rfc', 'max:150'],
            'horario_atencion' => ['nullable', 'string', 'max:200'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }
}
