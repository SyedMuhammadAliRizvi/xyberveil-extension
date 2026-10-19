<?php

namespace App\Http\Middleware;

use App\Models\Option;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateMonitoringToken
{
    /**
     * Guards the external online-users dashboard's read-only endpoint with
     * the same token the admin generates/regenerates on the Settings page
     * (Option key 'monitor_api_token') — not tied to any admin user/session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) (Option::where('key', 'monitor_api_token')->first()->value ?? '');
        $provided = (string) $request->bearerToken();

        if ($expected === '' || $provided === '' || ! hash_equals($expected, $provided)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
