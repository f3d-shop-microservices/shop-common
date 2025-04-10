<?php declare(strict_types=1);

namespace Shop\Common\ServiceDiscovery;

use Shop\Common\ServiceDiscovery\ServiceInstance;

interface ServiceLocatorInterface {
    public function getInstance(string $serviceName): ?ServiceInstance;
}