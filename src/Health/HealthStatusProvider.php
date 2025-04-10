<?php

namespace Shop\Common\Health;

use Shop\Common\Health\HealthProviderInterface;

class HealthStatusProvider implements HealthProviderInterface
{

    public function __construct(
        private string $serviceId,
        private ?string $serviceHost = null
    ) {
    }

    public function getStatus(): array
    {
        return [
            'status' => 'ok',
            'service_id' => $this->serviceId,
            'container_id' => $this->getDockerContainerId(),
            'host' => $this->serviceHost,
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
