<?php

namespace Shop\Common\Health;

class HealthStatusProvider
{

    public function getStatus(string $serviceId, ?string $host = null): array
    {
        return [
            'status' => 'ok',
            'service_id' => $serviceId,
            'container_id' => $this->getDockerContainerId(),
            'host' => $host,
            'time' => (new \DateTime())->format(\DateTime::ATOM),
        ];
    }

    private function getDockerContainerId(): string
    {
        $file = '/proc/self/cgroup';

        if (!file_exists($file)) {
            return 'n/a';
        }

        $content = file_get_contents($file);
        if (preg_match('/docker[-\/](?<id>[0-9a-f]{12,64})/', $content, $matches)) {
            return substr($matches['id'], 0, 12);
        }

        return 'unknown';
    }
}
