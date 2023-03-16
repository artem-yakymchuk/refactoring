<?php

declare(strict_types=1);

namespace App\Entity;

final class OperationWithCommission
{
    private Operation $operation;
    private float $commission;

    public function __construct(Operation $operation, float $commission)
    {
        $this->operation = $operation;
        $this->commission = $commission;
    }

    public static function create(Operation $operation, float $commission): self
    {
        return new self($operation, $commission);
    }

    public function getOperation(): Operation
    {
        return $this->operation;
    }

    public function getCommission(): float
    {
        return round($this->commission, 2, PHP_ROUND_HALF_UP);
    }
}
