<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Tramite;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTramiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $tramite = $this->route('tramite');
        return $tramite instanceof Tramite
            && ($this->user()?->can('tramites.update') ?? false);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        /** @var Tramite $tramite */
        $tramite = $this->route('tramite');

        return [
            'codigo' => ['sometimes', 'required', 'string', 'max:30',
                Rule::unique('tramites', 'codigo')->ignore($tramite->id)->whereNull('deleted_at')],
            'nombre' => ['sometimes', 'required', 'string', 'max:200'],
            'slug' => ['sometimes', 'required', 'string', 'max:220', 'regex:/^[a-z0-9-]+$/',
                Rule::unique('tramites', 'slug')->ignore($tramite->id)->whereNull('deleted_at')],
            'descripcion' => ['sometimes', 'required', 'string'],
            'requisitos' => ['sometimes', 'array', 'min:1'],
            'requisitos.*' => ['string'],
            'documentos_requeridos' => ['nullable', 'array'],
            'documentos_requeridos.*' => ['string'],
            'tiempo_estimado' => ['nullable', 'string', 'max:100'],
            'costo' => ['nullable', 'numeric', 'min:0'],
            'costo_variable' => ['sometimes', 'boolean'],
            'categoria' => ['nullable', 'string', 'max:80'],
            'dependencia_id' => ['sometimes', 'required', 'integer', Rule::exists('dependencias', 'id')->where('activo', true)],
            'canal_atencion' => ['nullable', 'string', 'max:100'],
            'url_govco' => ['nullable', 'url', 'max:255'],
            'publicado' => ['sometimes', 'boolean'],
        ];
    }
}
