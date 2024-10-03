<?php

declare(strict_types=1);

use app\common\Core\Application\UseCases\Commands\AssignedOrdersOnCouriers\AssignedOrdersOnCouriersCommandHandler;
use app\common\Core\Application\UseCases\Commands\AssignedOrdersOnCouriers\AssignedOrdersOnCouriersCommandHandlerInterface;
use app\common\Core\Application\UseCases\Commands\CreateOrder\CreateOrderCommandHandler;
use app\common\Core\Application\UseCases\Commands\CreateOrder\CreateOrderCommandHandlerInterface;
use app\common\Core\Application\UseCases\Commands\MoveCouriers\MoveCouriersCommandHandler;
use app\common\Core\Application\UseCases\Commands\MoveCouriers\MoveCouriersCommandHandlerInterface;
use app\common\Core\Application\UseCases\Queries\GetBusyCouriers\GetBusyCouriersQueryHandler;
use app\common\Core\Application\UseCases\Queries\GetBusyCouriers\GetBusyCouriersQueryHandlerInterface;
use app\common\Core\Application\UseCases\Queries\GetUncompletedOrders\GetUncompletedOrdersQueryHandler;
use app\common\Core\Application\UseCases\Queries\GetUncompletedOrders\GetUncompletedOrdersQueryHandlerInterface;
use app\common\Core\Domain\Services\Dispatch\DispatchService;
use app\common\Core\Domain\Services\Dispatch\DispatchServiceInterface;
use app\common\Core\Ports\CourierRepositoryInterface;
use app\common\Core\Ports\OrderRepositoryInterface;
use app\common\Infrastructure\Adapters\Postgres\Repositories\CourierRepository;
use app\common\Infrastructure\Adapters\Postgres\Repositories\OrderRepository;
use common\Infrastructure\Adapters\Postgres\UnitOfWork;
use common\Infrastructure\Adapters\Postgres\UnitOfWorkInterface;

return [
    'definitions' => [
        AssignedOrdersOnCouriersCommandHandlerInterface::class => AssignedOrdersOnCouriersCommandHandler::class,
        CreateOrderCommandHandlerInterface::class => CreateOrderCommandHandler::class,
        MoveCouriersCommandHandlerInterface::class => MoveCouriersCommandHandler::class,
        GetBusyCouriersQueryHandlerInterface::class => GetBusyCouriersQueryHandler::class,
        GetUncompletedOrdersQueryHandlerInterface::class => GetUncompletedOrdersQueryHandler::class,
        DispatchServiceInterface::class => DispatchService::class,
        CourierRepositoryInterface::class => CourierRepository::class,
        OrderRepositoryInterface::class => OrderRepository::class,
        UnitOfWorkInterface::class => UnitOfWork::class,
    ],
];