<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Resolución 1519/2020 Anexo 2 ítem 4.a — Formato +57.
 *  +57 5 4200100   (fijo: indicativo 1 dígito + 7 dígitos)
 *  +57 1 8000 12345 (línea 018000)
 *  +57 300 1234567 (móvil)
 */
final class TelefonoColombia implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('El campo :attribute debe ser una cadena.');
            return;
        }

        $pattern = '/^\+57\s(?:\d\s\d{7}|1\s8000\s\d{4,5}|3\d{2}\s\d{7})$/';

        if (preg_match($pattern, $value) !== 1) {
            $fail('El campo :attribute debe iniciar con +57 y seguir el formato establecido por la Resolución 1519/2020 (ej. +57 5 4200100).');
        }
    }
}
