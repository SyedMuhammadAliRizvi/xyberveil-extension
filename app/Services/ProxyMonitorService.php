<?php

namespace App\Services;

use App\Services\Proxy\ProxyAdminService;

class ProxyMonitorService
{
    public function __construct(private ProxyAdminService $adminService) {}

    public function dashboard(): array
    {
        return $this->adminService->dashboard();
    }

    public function users(): array
    {
        return $this->adminService->users();
    }

    public function userAction(int $id, string $action, ?string $notes = null): array
    {
        return $this->adminService->userAction($id, $action, $notes);
    }

    public function servers(): array
    {
        return $this->adminService->servers();
    }

    public function createServer(array $data): array
    {
        return $this->adminService->createServer($data);
    }

    public function updateServer(int $id, array $data): array
    {
        return $this->adminService->updateServer($id, $data);
    }

    public function deleteServer(int $id): void
    {
        $this->adminService->deleteServer($id);
    }

    public function setupServer(int $id): array
    {
        return $this->adminService->setupServer($id);
    }

    public function syncServer(int $id): array
    {
        return $this->adminService->syncServer($id);
    }

    public function serverDetail(int $id): array
    {
        return $this->adminService->serverDetail($id);
    }

    public function serverManageStats(int $id): array
    {
        return $this->adminService->serverManageStats($id);
    }

    public function sessions(array $params = []): array
    {
        return $this->adminService->sessions($params);
    }

    public function userDetail(int $id): array
    {
        return $this->adminService->userDetail($id);
    }

    public function deleteUserDevice(int $userId, int $deviceId): void
    {
        $this->adminService->deleteUserDevice($userId, $deviceId);
    }

    public function updateUserDevice(int $userId, int $deviceId, bool $isAllowed): array
    {
        return $this->adminService->updateUserDevice($userId, $deviceId, $isAllowed);
    }
}
