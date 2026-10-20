<?php

namespace App\Services\Proxy;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;

class ProxyTokenService
{
    public function createAccessToken(int $userId, string $role): string
    {
        $now = time();
        $ttl = config('proxy.access_token_ttl_minutes', 60) * 60;

        return JWT::encode([
            'sub' => (string) $userId,
            'role' => $role,
            'type' => 'access',
            'iat' => $now,
            'exp' => $now + $ttl,
        ], $this->secret(), 'HS256');
    }

    /**
     * @return array{sub: string, role: string, type: string}
     */
    public function decodeAccessToken(string $token): array
    {
        $payload = JWT::decode($token, new Key($this->secret(), 'HS256'));

        return (array) $payload;
    }

    public function createRefreshToken(): string
    {
        return Str::random(64);
    }

    private function secret(): string
    {
        $secret = (string) config('proxy.secret_key');

        if ($secret === '') {
            throw new \RuntimeException('PROXY_SECRET_KEY is not configured.');
        }

        return $secret;
    }
}
