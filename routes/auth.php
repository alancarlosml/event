<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// Route::get('login', 'App\Http\Controllers\Auth\AuthenticatedSessionController@create')->name('login');

Route::middleware('guest')->group(function () {

    Route::get('cadastro/sucesso', [RegisteredUserController::class, 'verify'])->name('register.success');

    Route::get('painel/cadastrar', [RegisteredUserController::class, 'create'])->name('register');

    Route::post('painel/cadastrar', [RegisteredUserController::class, 'store']);

    Route::get('painel/login', [AuthenticatedSessionController::class, 'create'])->name('login');

    Route::post('painel/login', [AuthenticatedSessionController::class, 'store'])->name('login');

    Route::get('painel/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');

    Route::post('painel/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware(['guest'])->name('password.email');

    Route::get('painel/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');

    Route::post('painel/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

});

Route::middleware('auth:participante')->group(function () {
    Route::get('painel/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');

    Route::get('painel/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('painel/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');

    Route::post('painel/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
