<?php declare(strict_types=1);

namespace Shop\Common\Contract;

interface HealthProviderInterface {
    public function getStatus(): array;
}