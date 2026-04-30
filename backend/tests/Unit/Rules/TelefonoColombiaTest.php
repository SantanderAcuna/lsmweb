<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use App\Rules\TelefonoColombia;
use PHPUnit\Framework\TestCase;

final class TelefonoColombiaTest extends TestCase
{
    /** @dataProvider validos */
    public function test_acepta_validos(string $valor): void
    {
        $fallo = false;
        (new TelefonoColombia())->validate('telefono', $valor, function () use (&$fallo): void {
            $fallo = true;
        });

        $this->assertFalse($fallo, "Esperado válido: {$valor}");
    }

    /** @dataProvider invalidos */
    public function test_rechaza_invalidos(string $valor): void
    {
        $fallo = false;
        (new TelefonoColombia())->validate('telefono', $valor, function () use (&$fallo): void {
            $fallo = true;
        });

        $this->assertTrue($fallo, "Esperado inválido: {$valor}");
    }

    /** @return array<string, array{0: string}> */
    public static function validos(): array
    {
        return [
            'fijo Santa Marta' => ['+57 5 4200100'],
            'línea 018000' => ['+57 1 8000 12345'],
            'móvil' => ['+57 300 1234567'],
        ];
    }

    /** @return array<string, array{0: string}> */
    public static function invalidos(): array
    {
        return [
            'sin prefijo' => ['5 4200100'],
            'con paréntesis' => ['(5) 420 0100'],
            'cadena vacía' => [''],
            'no numérico' => ['hola'],
        ];
    }
}
