<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\NaturalezaCargo;
use App\Enums\NivelFormacion;
use App\Enums\SectorExperiencia;
use App\Enums\TipoDocumento;
use App\Models\ServidorPublico;
use App\Rules\TelefonoColombia;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateServidorPublicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $servidor = $this->route('servidor');
        return $servidor instanceof ServidorPublico
            && ($this->user()?->can('servidores.update') ?? false);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        /** @var ServidorPublico $servidor */
        $servidor = $this->route('servidor');

        return [
            'nombres' => ['sometimes', 'required', 'string', 'max:100'],
            'apellidos' => ['sometimes', 'required', 'string', 'max:100'],
            'tipo_documento' => ['sometimes', 'required', new Enum(TipoDocumento::class)],
            'documento_identidad' => ['sometimes', 'required', 'string', 'max:20'],
            'municipio_nacimiento_id' => ['sometimes', 'required', 'integer', Rule::exists('municipios', 'id')],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'genero' => ['nullable', Rule::in(['M', 'F', 'O', 'NR'])],
            'dependencia_id' => ['sometimes', 'required', 'integer', Rule::exists('dependencias', 'id')->where('activo', true)],
            'cargo' => ['sometimes', 'required', 'string', 'max:150'],
            'naturaleza_cargo' => ['sometimes', 'required', new Enum(NaturalezaCargo::class)],
            'salario_basico' => ['nullable', 'numeric', 'min:0'],
            'correo_institucional' => [
                'sometimes', 'required', 'email:rfc,dns', 'max:150',
                'ends_with:@santamarta.gov.co',
                Rule::unique('servidores_publicos', 'correo_institucional')
                    ->ignore($servidor->id)
                    ->whereNull('deleted_at'),
            ],
            'correo_personal' => ['nullable', 'email:rfc', 'max:150'],
            'telefono_oficina' => ['nullable', new TelefonoColombia()],
            'extension' => ['nullable', 'string', 'max:10'],
            'sigep_url' => ['nullable', 'url', 'max:255'],
            'foto_url' => ['nullable', 'url', 'max:255'],
            'publicado' => ['sometimes', 'boolean'],

            'formaciones_academicas' => ['sometimes', 'array'],
            'formaciones_academicas.*.nivel' => ['required', new Enum(NivelFormacion::class)],
            'formaciones_academicas.*.titulo' => ['required', 'string', 'max:200'],
            'formaciones_academicas.*.institucion' => ['required', 'string', 'max:200'],
            'formaciones_academicas.*.pais_id' => ['required', 'integer', Rule::exists('paises', 'id')],
            'formaciones_academicas.*.ano_grado' => ['required', 'integer', 'min:1950', 'max:' . (int) date('Y')],

            'experiencias_profesionales' => ['sometimes', 'array'],
            'experiencias_profesionales.*.empresa' => ['required', 'string', 'max:200'],
            'experiencias_profesionales.*.cargo' => ['required', 'string', 'max:150'],
            'experiencias_profesionales.*.sector' => ['required', new Enum(SectorExperiencia::class)],
            'experiencias_profesionales.*.fecha_inicio' => ['required', 'date'],
            'experiencias_profesionales.*.fecha_fin' => ['nullable', 'date', 'after_or_equal:experiencias_profesionales.*.fecha_inicio'],
            'experiencias_profesionales.*.funciones' => ['nullable', 'string'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'correo_institucional.ends_with' => 'El correo debe pertenecer al dominio @santamarta.gov.co.',
        ];
    }
}
