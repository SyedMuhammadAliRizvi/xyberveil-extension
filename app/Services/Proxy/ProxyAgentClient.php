<?php

namespace App\Services\Proxy;

use App\Models\ProxyServer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProxyAgentClient
{
    public function call(ProxyServer $server, string $method, string $path, array $payload = []): bool
    {
        try {
            $response = $this->request($server, $method, $path, $payload);

            return in_array($response->status(), [200, 201, 204, 409], true);
        } catch (\Throwable $exception) {
            Log::warning('Proxy agent call failed', [
                'method' => $method,
                'path' => $path,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function get(ProxyServer $server, string $path): array
    {
        $response = $this->request($server, 'GET', $path);

        if (! $response->successful()) {
            throw new \RuntimeException("Agent request failed with status {$response->status()}");
        }

        return $response->json() ?? [];
    }

    private function request(ProxyServer $server, string $method, string $path, array $payload = []): \Illuminate\Http\Client\Response
    {
        $url = sprintf('http://%s:%d%s', $server->ip, $server->agent_port, $path);

        $client = Http::withHeaders([
            'X-API-Key' => $server->agent_key ?? '',
        ])->timeout(15);

        return match (strtoupper($method)) {
            'GET' => $client->get($url, $payload),
            'POST' => $client->post($url, $payload),
            'PUT' => $client->put($url, $payload),
            'PATCH' => $client->patch($url, $payload),
            'DELETE' => $client->delete($url, $payload),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };
    }
}
