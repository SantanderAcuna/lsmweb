<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Dependencia;
use App\Models\Noticia;
use App\Models\Pqrsd;
use App\Models\Sede;
use App\Models\ServidorPublico;
use App\Models\Tramite;
use App\Models\User;
use App\Policies\DependenciaPolicy;
use App\Policies\NoticiaPolicy;
use App\Policies\PqrsdPolicy;
use App\Policies\SedePolicy;
use App\Policies\ServidorPublicoPolicy;
use App\Policies\TramitePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

final class AuthServiceProvider extends ServiceProvider
{
    /** @var array<class-string, class-string> */
    protected $policies = [
        ServidorPublico::class => ServidorPublicoPolicy::class,
        Dependencia::class => DependenciaPolicy::class,
        User::class => UserPolicy::class,
        Tramite::class => TramitePolicy::class,
        Pqrsd::class => PqrsdPolicy::class,
        Noticia::class => NoticiaPolicy::class,
        Sede::class => SedePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Passport::tokensExpireIn(now()->addHours(2));
        Passport::refreshTokensExpireIn(now()->addDays(15));
        Passport::personalAccessTokensExpireIn(now()->addDays(30));
    }
}
