<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Proxy\ProxyAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class OnlineUsersController extends Controller
{
    public function __construct(private ProxyAdminService $proxy) {}

    /**
     * Shaped to match the old panel's GET /api/vps/online-users response
     * ({ vpn, proxy, timestamp }) so the existing quixure-online-users
     * dashboard can add this panel as just another "panel"-format source.
     * This project has no VPS/VPN fleet anymore, so "vpn" is always the
     * empty/zero shape — only "proxy" carries real data.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'vpn' => [
                'total_connected' => 0,
                'ikev2_connected' => 0,
                'openvpn_connected' => 0,
                'wireguard_connected' => 0,
                'fetched_at' => null,
                'servers' => [],
            ],
            'proxy' => [
                'fetched_at' => Carbon::now()->toISOString(),
                'error' => null,
                'stats' => $this->proxy->dashboard(),
                'sessions' => $this->proxy->sessions(),
            ],
            'timestamp' => Carbon::now()->toISOString(),
        ]);
    }
}
