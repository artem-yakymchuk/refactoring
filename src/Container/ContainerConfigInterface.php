<?php

declare(strict_types=1);

namespace App\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ContainerConfigInterface
{
    public function getContainer(): ContainerBuilder;
}
