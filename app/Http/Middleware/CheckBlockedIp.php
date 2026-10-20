<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedIp
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip_address ?? $request->ip();

        // Cache blocked IPs for 5 minutes to avoid a DB hit on every request
        $isBlocked = Cache::remember("blocked_ip:{$ip}", 300, function () use ($ip) {
            return BlockedIp::active()->where('ip_address', $ip)->exists();
        });

        if ($isBlocked) {
            return response()->json([
                'status' => false,
                'message' => 'Your IP address has been blocked. Please contact support.',
            ], 403);
        }

        return $next($request);
    }
}
