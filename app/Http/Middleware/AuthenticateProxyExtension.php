<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Proxy\ProxyTokenService;
use App\Support\ProxyApiResponse;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthenticateProxyExtension
{
    public function __construct(private ProxyTokenService $tokenService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization', '');

        if (! str_starts_with($header, 'Bearer ')) {
            return ProxyApiResponse::detail('Invalid or expired token', 401);
        }

        $token = trim(substr($header, 7));

        try {
            $payload = $this->tokenService->decodeAccessToken($token);

            if (($payload['type'] ?? null) !== 'access') {
                return ProxyApiResponse::detail('Wrong token type', 401);
            }

            $user = User::query()->find((int) $payload['sub']);

            if (! $user || ! $user->hasProxyAccess()) {
                return ProxyApiResponse::detail('User not found', 404);
            }

            $request->attributes->set('proxy_user', $user);

            return $next($request);
        } catch (ExpiredException|SignatureInvalidException $exception) {
            return ProxyApiResponse::detail('Invalid or expired token', 401);
        } catch (Throwable) {
            return ProxyApiResponse::detail('Invalid or expired token', 401);
        }
    }
}
