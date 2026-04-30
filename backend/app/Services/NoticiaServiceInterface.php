<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Noticia;
use App\Models\User;

interface NoticiaServiceInterface
{
    /** @param  array<string, mixed>  $data */
    public function crear(array $data, ?User $autor = null): Noticia;

    /** @param  array<string, mixed>  $data */
    public function actualizar(Noticia $noticia, array $data, ?User $autor = null): Noticia;

    public function eliminar(Noticia $noticia): bool;
}
