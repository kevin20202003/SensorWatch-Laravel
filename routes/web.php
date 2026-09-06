<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

    Route::get('/login/verificar', [AuthController::class, 'showVerificationForm'])->name('login.verify.form');
    Route::post('/login/verificar', [AuthController::class, 'verifyCode'])->name('login.verify');
    Route::post('/login/reenviar-codigo', [AuthController::class, 'resendCode'])->name('login.resend');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/cambiar-contrasena', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/cambiar-contrasena', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::get('/sensores/{section}', [SensorController::class, 'show'])
        ->whereIn('section', ['suelo', 'ambiente', 'clima'])
        ->name('sensores.show');

    Route::get('/predicciones', [SensorController::class, 'predictions'])->name('predicciones.index');

    Route::get('/umbrales', [SensorController::class, 'thresholds'])->name('umbrales.index');
    Route::post('/umbrales', [SensorController::class, 'storeThresholds'])->name('umbrales.store');

    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notificaciones.index');
    Route::delete('/notificaciones/{notification}', [NotificationController::class, 'destroy'])->name('notificaciones.destroy');
});
