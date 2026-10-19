<?php

namespace App\Http\Controllers\Api\Proxy;

use App\Http\Controllers\Controller;
use App\Services\Proxy\ProxyExtensionAuthService;
use App\Support\ProxyApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProxyAuthController extends Controller
{
    public function __construct(private ProxyExtensionAuthService $authService) {}

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'display_name' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return ProxyApiResponse::detail($validator->errors()->first() ?? 'Validation failed', 422);
        }

        $data = $this->authService->register($validator->validated());

        return response()->json($data, 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_key' => ['nullable', 'string'],
            'browser_name' => ['nullable', 'string'],
            'browser_version' => ['nullable', 'string'],
            'os_name' => ['nullable', 'string'],
            'os_version' => ['nullable', 'string'],
            'extension_version' => ['nullable', 'string'],
            'timezone' => ['nullable', 'string', 'max:64'],
        ]);

        if ($validator->fails()) {
            return ProxyApiResponse::detail($validator->errors()->first() ?? 'Validation failed', 422);
        }

        return response()->json($this->authService->login($this->authPayload($request)));
    }

    public function refresh(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => ['required', 'string'],
            'device_key' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return ProxyApiResponse::detail($validator->errors()->first() ?? 'Validation failed', 422);
        }

        return response()->json($this->authService->refresh($request->all()));
    }

    public function logout(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return ProxyApiResponse::detail($validator->errors()->first() ?? 'Validation failed', 422);
        }

        return response()->json($this->authService->logout($request->all()));
    }

    public function google(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'access_token' => ['required', 'string'],
            'device_key' => ['nullable', 'string'],
            'browser_name' => ['nullable', 'string'],
            'browser_version' => ['nullable', 'string'],
            'os_name' => ['nullable', 'string'],
            'os_version' => ['nullable', 'string'],
            'extension_version' => ['nullable', 'string'],
            'timezone' => ['nullable', 'string', 'max:64'],
        ]);

        if ($validator->fails()) {
            return ProxyApiResponse::detail($validator->errors()->first() ?? 'Validation failed', 422);
        }

        return response()->json($this->authService->googleLogin($this->authPayload($request)));
    }

    /**
     * Verifies the OTP against XyberVeil's v3 API itself (see
     * ProxyExtensionAuthService::verifyXyberveilOtp) rather than trusting
     * the caller — a bare email with no code is never enough to log in.
     */
    public function otpLogin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code' => ['required', 'string'],
            'device_key' => ['nullable', 'string'],
            'browser_name' => ['nullable', 'string'],
            'browser_version' => ['nullable', 'string'],
            'os_name' => ['nullable', 'string'],
            'os_version' => ['nullable', 'string'],
            'extension_version' => ['nullable', 'string'],
            'timezone' => ['nullable', 'string', 'max:64'],
        ]);

        if ($validator->fails()) {
            return ProxyApiResponse::detail($validator->errors()->first() ?? 'Validation failed', 422);
        }

        return response()->json($this->authService->otpLogin($this->authPayload($request)));
    }

    /**
     * UUID already confirmed via the XyberVeil v3 continue-as-guest call before this is called.
     */
    public function guest(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'uuid' => ['required', 'string'],
            'device_key' => ['nullable', 'string'],
            'browser_name' => ['nullable', 'string'],
            'browser_version' => ['nullable', 'string'],
            'os_name' => ['nullable', 'string'],
            'os_version' => ['nullable', 'string'],
            'extension_version' => ['nullable', 'string'],
            'timezone' => ['nullable', 'string', 'max:64'],
        ]);

        if ($validator->fails()) {
            return ProxyApiResponse::detail($validator->errors()->first() ?? 'Validation failed', 422);
        }

        return response()->json($this->authService->guestLogin($this->authPayload($request)));
    }

    /**
     * @return array<string, mixed>
     */
    private function authPayload(Request $request): array
    {
        return array_merge($request->all(), [
            'ip_address' => $request->input('ip_address') ?: $request->ip(),
        ]);
    }
}
