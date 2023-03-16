<?php

declare(strict_types=1);

namespace App\Rate\Http;

final class ClientConfiguration
{
    private string $access_key;

    public function __construct(string $access_key)
    {
        $this->access_key = $access_key;
    }

    public function getAccessKey(): string
    {
        return $this->access_key;
    }
}
