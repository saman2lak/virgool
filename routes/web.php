<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;




// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

// require __DIR__.'/auth.php';

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')->name('login');

// Route::get('/verify1', [EmailVerificationPromptController::class, '__invoke'])
//                 ->middleware('auth')
//                 ->name('verification.notice');

Route::get('/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/reset/password/{token}', function () {
    return view('welcome');
})->middleware('guest')->name('password.reset');

Route::get('/link/{post:short_link}', [PostController::class, 'short_link']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');