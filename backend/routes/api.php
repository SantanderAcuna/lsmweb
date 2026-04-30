<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Admin\NoticiaController as AdminNoticia;
use App\Http\Controllers\Api\Admin\PqrsdController as AdminPqrsd;
use App\Http\Controllers\Api\Admin\ServidorPublicoController as AdminServidor;
use App\Http\Controllers\Api\Admin\TramiteController as AdminTramite;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Public\NoticiaController as PublicNoticia;
use App\Http\Controllers\Api\Public\PqrsdController as PublicPqrsd;
use App\Http\Controllers\Api\Public\ServidorPublicoController as PublicServidor;
use App\Http\Controllers\Api\Public\TramiteController as PublicTramite;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    /* ---------------- Auth ---------------- */
    Route::prefix('auth')->group(function (): void {
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset');
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('me', [AuthController::class, 'me'])->name('auth.me');
    });

    /* -------------- Públicos -------------- */
    Route::prefix('servidores-publicos')->group(function (): void {
        Route::get('/', [PublicServidor::class, 'index'])->name('public.servidores.index');
        Route::get('export', [PublicServidor::class, 'export'])->name('public.servidores.export');
        Route::get('{id}', [PublicServidor::class, 'show'])->whereNumber('id')->name('public.servidores.show');
    });

    Route::prefix('tramites')->group(function (): void {
        Route::get('/', [PublicTramite::class, 'index'])->name('public.tramites.index');
        Route::get('{slug}', [PublicTramite::class, 'show'])->name('public.tramites.show');
    });

    Route::prefix('pqrsd')->group(function (): void {
        Route::post('/', [PublicPqrsd::class, 'store'])->name('public.pqrsd.store');
        Route::get('consultar/{radicado}', [PublicPqrsd::class, 'consultar'])->name('public.pqrsd.consultar');
    });

    Route::prefix('noticias')->group(function (): void {
        Route::get('/', [PublicNoticia::class, 'index'])->name('public.noticias.index');
        Route::get('{slug}', [PublicNoticia::class, 'show'])->name('public.noticias.show');
    });

    /* -------------- Admin -------------- */
    Route::prefix('admin')->group(function (): void {
        Route::apiResource('servidores-publicos', AdminServidor::class)
            ->parameters(['servidores-publicos' => 'servidor'])
            ->names([
                'index' => 'admin.servidores.index',
                'store' => 'admin.servidores.store',
                'show' => 'admin.servidores.show',
                'update' => 'admin.servidores.update',
                'destroy' => 'admin.servidores.destroy',
            ]);

        Route::apiResource('tramites', AdminTramite::class);

        Route::apiResource('noticias', AdminNoticia::class);

        Route::prefix('pqrsd')->group(function (): void {
            Route::get('/', [AdminPqrsd::class, 'index'])->name('admin.pqrsd.index');
            Route::get('{pqrsd}', [AdminPqrsd::class, 'show'])->name('admin.pqrsd.show');
            Route::post('{pqrsd}/asignar', [AdminPqrsd::class, 'asignar'])->name('admin.pqrsd.asignar');
            Route::post('{pqrsd}/responder', [AdminPqrsd::class, 'responder'])->name('admin.pqrsd.responder');
            Route::patch('{pqrsd}/estado', [AdminPqrsd::class, 'cambiarEstado'])->name('admin.pqrsd.estado');
        });
    });
});
