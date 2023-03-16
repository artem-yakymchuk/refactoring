<?php

declare(strict_types=1);

namespace App\Container;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class ContainerConfig implements ContainerConfigInterface
{
    public function getContainer(): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();
        $loader = new XmlFileLoader($containerBuilder, new FileLocator('./config'));
        $loader->load('services.xml');
        $containerBuilder->compile();

        return $containerBuilder;
    }
}
