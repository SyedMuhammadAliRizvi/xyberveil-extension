<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\OnlineUsersController;
use App\Http\Controllers\Api\Proxy\ProxyAuthController;
use App\Http\Controllers\Api\Proxy\ProxyExtensionController;
use App\Http\Controllers\Api\ResourceController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
    Route::middleware(['guest', 'ip.blocked'])->group(function () {
        Route::post('/forgot-password', [AccountController::class, 'sendResetLink'])->name('api.password.reset')->middleware('throttle:6,1');
    });

    Route::middleware('monitoring.token')->prefix('vps')->group(function () {
        Route::get('/online-users', [OnlineUsersController::class, 'index'])->name('api.vps.online-users');
    });

    Route::get('/notifications', [ResourceController::class, 'notifications'])->name('api.notifications');
    Route::post('/feedback/store', [ResourceController::class, 'addFeedback'])->name('api.feedback.add');
    Route::get('/plans', [ResourceController::class, 'plans']);
    Route::get('/options', [ResourceController::class, 'options']);

    Route::prefix('proxy')->group(function () {
        Route::post('/auth/register', [ProxyAuthController::class, 'register']);
        Route::post('/auth/login', [ProxyAuthController::class, 'login']);
        Route::post('/auth/refresh', [ProxyAuthController::class, 'refresh']);
        Route::post('/auth/logout', [ProxyAuthController::class, 'logout']);
        Route::post('/auth/google', [ProxyAuthController::class, 'google']);
        Route::post('/auth/otp-login', [ProxyAuthController::class, 'otpLogin']);
        Route::post('/auth/guest', [ProxyAuthController::class, 'guest']);

        Route::middleware('proxy.extension')->group(function () {
            Route::get('/me', [ProxyExtensionController::class, 'me']);
            Route::get('/servers', [ProxyExtensionController::class, 'servers']);
            Route::get('/credentials/{serverId}', [ProxyExtensionController::class, 'credentials']);
            Route::post('/sessions/start', [ProxyExtensionController::class, 'startSession']);
            Route::post('/sessions/{sessionId}/end', [ProxyExtensionController::class, 'endSession']);
            Route::post('/devices/register', [ProxyExtensionController::class, 'registerDevice']);
            Route::get('/devices', [ProxyExtensionController::class, 'devices']);
            Route::get('/devices/status', [ProxyExtensionController::class, 'deviceStatus']);
        });
    });
});
