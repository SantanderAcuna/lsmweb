<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Noticia;
use App\Models\User;

final class NoticiaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('noticias.view');
    }

    public function view(User $user, Noticia $noticia): bool
    {
        return $user->can('noticias.view');
    }

    public function create(User $user): bool
    {
        return $user->can('noticias.create');
    }

    public function update(User $user, Noticia $noticia): bool
    {
        return $user->can('noticias.update');
    }

    public function delete(User $user, Noticia $noticia): bool
    {
        return $user->can('noticias.delete');
    }

    public function publish(User $user, Noticia $noticia): bool
    {
        return $user->can('noticias.publish');
    }
}
