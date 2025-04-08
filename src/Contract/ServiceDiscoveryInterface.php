<?php declare(strict_types=1);

namespace Shop\Common\Contract;

interface ServiceDiscoveryInterface {
    public function register(): void;
}