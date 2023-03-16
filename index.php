<?php

use App\App;
use App\Container\ContainerConfig;

require 'vendor/autoload.php';

$containerConfig = new ContainerConfig();
$container = $containerConfig->getContainer();

/** @var App $app */
$app = $container->get(App::class);

$app->execute($argv[1]);
