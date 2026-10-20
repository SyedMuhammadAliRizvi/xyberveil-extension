<?php

namespace App\Http\Controllers\Api\Proxy;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Proxy\ProxyExtensionService;
use App\Support\ProxyApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProxyExtensionController extends Controller
{
    public function __construct(private ProxyExtensionService $extensionService) {}

    public function me(Request $request): JsonResponse
    {
        return response()->json($this->extensionService->me($this->proxyUser($request)));
    }

    public function servers(Request $request): JsonResponse
    {
        return response()->json($this->extensionService->servers($this->proxyUser($request)));
    }

    public function credentials(Request $request, int $serverId): JsonResponse
    {
        return response()->json($this->extensionService->credentials($this->proxyUser($request), $serverId));
    }

    public function startSession(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'server_id' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return ProxyApiResponse::detail($validator->errors()->first() ?? 'Validation failed', 422);
        }

        return response()->json($this->extensionService->startSession(
            $this->proxyUser($request),
            (int) $request->input('server_id'),
            $request,
        ));
    }

    public function endSession(Request $request, int $sessionId): JsonResponse
    {
        return response()->json($this->extensionService->endSession($this->proxyUser($request), $sessionId));
    }

    public function registerDevice(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'device_key' => ['required', 'string', 'min:8', 'max:64'],
            'browser_name' => ['required', 'string', 'max:80'],
            'browser_version' => ['nullable', 'string', 'max:40'],
            'os_name' => ['nullable', 'string', 'max:80'],
            'os_version' => ['nullable', 'string', 'max:40'],
            'extension_version' => ['nullable', 'string', 'max:20'],
            'timezone' => ['nullable', 'string', 'max:64'],
        ]);

        if ($validator->fails()) {
            return ProxyApiResponse::detail($validator->errors()->first() ?? 'Validation failed', 422);
        }

        return response()->json($this->extensionService->registerDevice(
            $this->proxyUser($request),
            array_merge($request->all(), [
                'ip_address' => $request->input('ip_address') ?: $request->ip(),
            ]),
        ));
    }

    public function devices(Request $request): JsonResponse
    {
        return response()->json($this->extensionService->listDevices(
            $this->proxyUser($request),
            $request->query('current'),
        ));
    }

    public function deviceStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'device_key' => ['required', 'string', 'min:8', 'max:64'],
        ]);

        if ($validator->fails()) {
            return ProxyApiResponse::detail($validator->errors()->first() ?? 'Validation failed', 422);
        }

        return response()->json($this->extensionService->deviceStatus(
            $this->proxyUser($request),
            (string) $request->query('device_key'),
        ));
    }

    private function proxyUser(Request $request): User
    {
        /** @var User $user */
        $user = $request->attributes->get('proxy_user');

        return $user;
    }
}
