<?php

declare(strict_types=1);

namespace App\Enums;

enum NaturalezaCargo: string
{
    case LIBRE_NOMBRAMIENTO = 'LIBRE_NOMBRAMIENTO';
    case CARRERA = 'CARRERA';
    case PROVISIONAL = 'PROVISIONAL';
    case CONTRATO_PRESTACION = 'CONTRATO_PRESTACION';
    case ELECCION_POPULAR = 'ELECCION_POPULAR';

    public function label(): string
    {
        return match ($this) {
            self::LIBRE_NOMBRAMIENTO => 'Libre nombramiento y remoción',
            self::CARRERA => 'Carrera administrativa',
            self::PROVISIONAL => 'Provisional',
            self::CONTRATO_PRESTACION => 'Contrato de prestación de servicios',
            self::ELECCION_POPULAR => 'Elección popular',
        };
    }
}
