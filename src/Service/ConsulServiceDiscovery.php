<?php declare(strict_types=1);

namespace Shop\Common\Service;

use Shop\Common\Contract\ServiceDiscoveryInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ConsulServiceDiscovery implements ServiceDiscoveryInterface {
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $serviceHost,
        private string $serviceName,
        private int $servicePort,
        private string $consulHost
    ) {}

    public function register(): void
    {
        $serviceId = $this->serviceName . '-1';

        $body = [
            'ID' => $serviceId,
            'Name' => $this->serviceName,
            'Address' => $this->serviceHost,
            'Port' => $this->servicePort,
            'Check' => [
                'HTTP' => "http://{$this->serviceHost}:{$this->servicePort}/health",
                'Interval' => '10s',
                'Timeout' => '2s'
            ]
        ];

        try {
            $this->httpClient->request('PUT',"http://{$this->consulHost}:8500/v1/agent/service/register", [
                'json' => $body
            ]);
            echo "[Consul] Сервис {$serviceId} зарегистрирован под именем {$this->serviceName}" . PHP_EOL;
        } catch (\Throwable $e) {
            echo '[Consul] Ошибка регистрации: ' . $e->getMessage() . PHP_EOL;
        }
    }
}