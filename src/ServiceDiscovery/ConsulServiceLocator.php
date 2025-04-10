<?php declare(strict_types=1);

namespace Shop\Common\ServiceDiscovery;

use Shop\Common\ServiceDiscovery\ServiceLocatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ConsulServiceLocator implements ServiceLocatorInterface {
    private array $cache = [];

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $consulHost
    ) {}

    public function getInstance(string $serviceName): ?ServiceInstance
    {
        if (isset($this->cache[$serviceName])) {
            return $this->cache[$serviceName];
        }

        try {
            $response = $this->httpClient->request('GET', "http://{$this->consulHost}:8500/v1/health/service/{$serviceName}?passing=true");
            $list = $response->toArray();

            if (empty($list)) {
                return null;
            }

            $chosen = $list[array_rand($list)];

            $meta = $chosen['Service']['Meta'] ?? [];
            $address = $meta['public_address'] ?? $chosen['Service']['Address'] ?? $chosen['Node']['Address'];
            $port = (int) ($meta['public_port'] ?? $chosen['Service']['Port']);

            $instance = new ServiceInstance($address, $port);
            $this->cache[$serviceName] = $instance;

            return $instance;

        } catch (\Throwable $e) {
            echo "[Consul] Ошибка получения инстанса {$serviceName}: {$e->getMessage()}" . PHP_EOL;
            return null;
        }
    }
}