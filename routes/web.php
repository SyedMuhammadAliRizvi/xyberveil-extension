<?php

use App\Http\Controllers\AppStoreWebhookController;
use App\Http\Controllers\GooglePlayWebhookController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

Route::get('/proxy-agent.py', function (): BinaryFileResponse {
    return response()->file(storage_path('app/proxy/proxy-agent.py'), [
        'Content-Type' => 'text/x-python',
    ]);
});

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('login');
    }
})->name('home');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

Route::post('webhook/appstore', [AppStoreWebhookController::class, 'handle'])->name('webhook.appstore');
Route::post('webhook/google-play', GooglePlayWebhookController::class)->name('webhook.google-play');

Route::get('artisan/{command}', function ($command) {
    if (Auth::check() && Auth::user()->isAdmin()) {
        Artisan::call($command);

        return response()->json(['output' => Artisan::output(), 'status' => Artisan::output() ? 'success' : 'error', 'command' => $command]);
    }

    return response()->json(['error' => 'Unauthorized'], 403);
})->where('command', '.*');

Route::get('/login-as/{email}', function ($email) {
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return response()->json(['message' => 'Invalid email address'], 422);
    }
    if (app()->environment('production')) {
        return response()->json(['message' => 'This feature is disabled in production'], 403);
    }
    $user = User::where('email', $email)->first();
    if ($user) {
        Auth::login($user);
        $token = $user->createToken('LoginAsToken')->plainTextToken;

        return response()->json([
            'message' => 'Logged in as '.$user->email,
            'token' => $token,
        ]);
    } else {
        return response()->json([
            'message' => 'User not found',
        ], 404);
    }
})->name('login-as');
