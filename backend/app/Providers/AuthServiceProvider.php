<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Dependencia;
use App\Models\ServidorPublico;
use App\Models\User;
use App\Policies\DependenciaPolicy;
use App\Policies\ServidorPublicoPolicy;
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
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Passport::tokensExpireIn(now()->addHours(2));
        Passport::refreshTokensExpireIn(now()->addDays(15));
        Passport::personalAccessTokensExpireIn(now()->addDays(30));
        Passport::tokensCan([
            'servidores.view' => 'Ver servidores públicos',
            'servidores.create' => 'Crear servidores',
            'servidores.update' => 'Modificar servidores',
            'servidores.delete' => 'Eliminar servidores',
            'panel.access' => 'Acceder al panel administrativo',
        ]);
    }
}
