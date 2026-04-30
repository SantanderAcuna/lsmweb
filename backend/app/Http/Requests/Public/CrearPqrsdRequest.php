<?php

declare(strict_types=1);

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrearPqrsdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'tipo' => ['required', Rule::in(['PETICION', 'QUEJA', 'RECLAMO', 'SUGERENCIA', 'DENUNCIA', 'INFORMACION'])],
            'asunto' => ['required', 'string', 'max:200'],
            'descripcion' => ['required', 'string', 'min:10'],
            'anonima' => ['sometimes', 'boolean'],
            'solicitante_nombre' => ['required_if:anonima,false', 'nullable', 'string', 'max:150'],
            'solicitante_documento' => ['required_if:anonima,false', 'nullable', 'string', 'max:20'],
            'solicitante_correo' => ['required_if:anonima,false', 'nullable', 'email:rfc'],
            'solicitante_telefono' => ['nullable', 'string', 'max:30'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
            'tipo.in' => 'Tipo de PQRSD no válido.',
            'solicitante_nombre.required_if' => 'El nombre es obligatorio cuando la solicitud no es anónima.',
            'solicitante_documento.required_if' => 'El documento es obligatorio cuando la solicitud no es anónima.',
            'solicitante_correo.required_if' => 'El correo es obligatorio cuando la solicitud no es anónima.',
        ];
    }
}
