<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Admin\ServidorPublicoController as AdminServidorController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Public\ServidorPublicoController as PublicServidorController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    /* ----------- Auth ----------- */
    Route::prefix('auth')->group(function (): void {
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset');
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('me', [AuthController::class, 'me'])->name('auth.me');
    });

    /* ----------- Público ----------- */
    Route::prefix('servidores-publicos')->group(function (): void {
        Route::get('/', [PublicServidorController::class, 'index'])->name('public.servidores.index');
        Route::get('export', [PublicServidorController::class, 'export'])->name('public.servidores.export');
        Route::get('{id}', [PublicServidorController::class, 'show'])->whereNumber('id')->name('public.servidores.show');
    });

    /* ----------- Admin ----------- */
    Route::prefix('admin')->group(function (): void {
        Route::apiResource('servidores-publicos', AdminServidorController::class)
            ->parameters(['servidores-publicos' => 'servidor'])
            ->names([
                'index' => 'admin.servidores.index',
                'store' => 'admin.servidores.store',
                'show' => 'admin.servidores.show',
                'update' => 'admin.servidores.update',
                'destroy' => 'admin.servidores.destroy',
            ]);
    });
});
