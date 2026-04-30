<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\ServidorPublicoRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\EloquentServidorPublicoRepository;
use App\Repositories\EloquentUserRepository;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Auth\PassportAuthService;
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
        UserRepositoryInterface::class => EloquentUserRepository::class,
        ServidorPublicoRepositoryInterface::class => EloquentServidorPublicoRepository::class,
        ServidorPublicoServiceInterface::class => ServidorPublicoService::class,
        AuthServiceInterface::class => PassportAuthService::class,
    ];
}
