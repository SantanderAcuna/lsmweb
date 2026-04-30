<?php

declare(strict_types=1);

namespace App\Enums;

enum NivelFormacion: string
{
    case BACHILLER = 'BACHILLER';
    case TECNICO = 'TECNICO';
    case TECNOLOGO = 'TECNOLOGO';
    case PROFESIONAL = 'PROFESIONAL';
    case ESPECIALIZACION = 'ESPECIALIZACION';
    case MAESTRIA = 'MAESTRIA';
    case DOCTORADO = 'DOCTORADO';
}
