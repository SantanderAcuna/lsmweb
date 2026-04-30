<?php

declare(strict_types=1);

namespace App\Enums;

enum SectorExperiencia: string
{
    case PUBLICO = 'PUBLICO';
    case PRIVADO = 'PRIVADO';
    case MIXTO = 'MIXTO';
    case ONG = 'ONG';
    case ACADEMICO = 'ACADEMICO';
}
