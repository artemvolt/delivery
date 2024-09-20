<?php

declare(strict_types=1);

use Core\Ports\CourierRepositoryInterface;
use Core\Ports\OrderRepositoryInterface;
use Infrastructure\Adapters\Postgres\Repositories\CourierRepository;
use Infrastructure\Adapters\Postgres\Repositories\OrderRepository;
use Yiisoft\Di\ContainerConfig;

$config = ContainerConfig::create()
    ->withDefinitions([
        CourierRepositoryInterface::class => CourierRepository::class,
        OrderRepositoryInterface::class => OrderRepository::class,
    ]);