<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('usuarios.create') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc,dns', 'max:150', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => [
                'required',
                Password::min(12)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
            'estado' => ['sometimes', Rule::in(['ACTIVO', 'INACTIVO', 'BLOQUEADO'])],
            'roles' => ['array'],
            'roles.*' => ['string', Rule::exists('roles', 'name')->where('guard_name', 'api')],
        ];
    }
}
