<?php

declare(strict_types=1);

namespace App\Enums;

enum TipoDocumento: string
{
    case CC = 'CC';
    case CE = 'CE';
    case PA = 'PA';
    case TI = 'TI';

    public function label(): string
    {
        return match ($this) {
            self::CC => 'Cédula de Ciudadanía',
            self::CE => 'Cédula de Extranjería',
            self::PA => 'Pasaporte',
            self::TI => 'Tarjeta de Identidad',
        };
    }
}
