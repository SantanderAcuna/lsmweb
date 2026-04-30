<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\DependenciaRepositoryInterface;
use App\Repositories\Contracts\NoticiaRepositoryInterface;
use App\Repositories\Contracts\PqrsdRepositoryInterface;
use App\Repositories\Contracts\SedeRepositoryInterface;
use App\Repositories\Contracts\ServidorPublicoRepositoryInterface;
use App\Repositories\Contracts\TramiteRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\EloquentDependenciaRepository;
use App\Repositories\EloquentNoticiaRepository;
use App\Repositories\EloquentPqrsdRepository;
use App\Repositories\EloquentSedeRepository;
use App\Repositories\EloquentServidorPublicoRepository;
use App\Repositories\EloquentTramiteRepository;
use App\Repositories\EloquentUserRepository;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Auth\PassportAuthService;
use App\Services\PqrsdService;
use App\Services\PqrsdServiceInterface;
use App\Services\ServidorPublicoService;
use App\Services\ServidorPublicoServiceInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Bind interfaces ↔ implementaciones (Dependency Inversion - SOLID).
 */
final class RepositoryServiceProvider extends ServiceProvider
{
    /** @var array<class-string, class-string> */
    public array $bindings = [
        // Repositories
        UserRepositoryInterface::class => EloquentUserRepository::class,
        ServidorPublicoRepositoryInterface::class => EloquentServidorPublicoRepository::class,
        TramiteRepositoryInterface::class => EloquentTramiteRepository::class,
        PqrsdRepositoryInterface::class => EloquentPqrsdRepository::class,
        NoticiaRepositoryInterface::class => EloquentNoticiaRepository::class,
        SedeRepositoryInterface::class => EloquentSedeRepository::class,
        DependenciaRepositoryInterface::class => EloquentDependenciaRepository::class,
        // Services
        ServidorPublicoServiceInterface::class => ServidorPublicoService::class,
        PqrsdServiceInterface::class => PqrsdService::class,
        AuthServiceInterface::class => PassportAuthService::class,
    ];
}
