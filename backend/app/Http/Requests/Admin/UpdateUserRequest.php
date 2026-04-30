<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('user') instanceof User
            && ($this->user()?->can('usuarios.update') ?? false);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        /** @var User $user */
        $user = $this->route('user');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:120'],
            'email' => ['sometimes', 'required', 'email:rfc,dns', 'max:150',
                Rule::unique('users', 'email')->ignore($user->id)->whereNull('deleted_at')],
            'password' => ['nullable',
                Password::min(12)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'estado' => ['sometimes', Rule::in(['ACTIVO', 'INACTIVO', 'BLOQUEADO'])],
            'roles' => ['sometimes', 'array'],
            'roles.*' => ['string', Rule::exists('roles', 'name')->where('guard_name', 'api')],
        ];
    }
}
