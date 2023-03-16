<?php

declare(strict_types=1);

namespace App\Rate\Entity;

final class ExchangeRate
{
    private string $symbol;
    private float $rate;

    public function __construct(string $symbol, float $rate)
    {
        $this->symbol = $symbol;
        $this->rate = $rate;
    }

    public static function create(string $symbol, float $rate): self
    {
        return new self($symbol, $rate);
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
