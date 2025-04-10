<?php declare(strict_types=1);

namespace Shop\Common\ServiceDiscovery;

interface ServiceDiscoveryInterface {
    public function register(): void;
}