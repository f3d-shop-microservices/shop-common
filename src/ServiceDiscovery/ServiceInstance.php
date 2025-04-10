<?php declare(strict_types=1);

namespace Shop\Common\ServiceDiscovery;

final class ServiceInstance {
    public function __construct(
        public readonly string $address,
        public readonly int $port,
    ) {}

    public function getBaseUri(): string
    {
        return "http://{$this->address}:{$this->port}";
    }
}