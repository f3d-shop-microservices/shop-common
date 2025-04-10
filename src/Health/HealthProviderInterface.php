<?php declare(strict_types=1);

namespace Shop\Common\Health;

interface HealthProviderInterface {
    public function getStatus(): array;
}