<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;
use SocialiteProviders\Apple\Provider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('apple', Provider::class);
        });

        User::observe(UserObserver::class);

        LogViewer::auth(fn (Request $request) => $request->user()?->isAdmin());

        RateLimiter::for('api', function (Request $request) {
            $maxAttempts = 60;
            $key = optional($request->user())->id ?: $request->ip();

            return Limit::perMinute($maxAttempts)->by($key)->response(function (Request $request, array $headers) {
                return response()->json([
                    'message' => "Too many requests. Remaining attempts: {$headers['X-RateLimit-Remaining']}. Try again in {$headers['Retry-After']} seconds.",
                ], 429, $headers);
            });
        });
    }
}
