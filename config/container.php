<?php

declare(strict_types=1);

use app\common\Core\Application\DomainEventHandlers\OrderCompleted\OrderCompletedDomainEventHandler;
use app\common\Core\Application\UseCases\Commands\AssignedOrdersOnCouriers\AssignedOrdersOnCouriersCommandHandler;
use app\common\Core\Application\UseCases\Commands\AssignedOrdersOnCouriers\AssignedOrdersOnCouriersCommandHandlerInterface;
use app\common\Core\Application\UseCases\Commands\CreateOrder\CreateOrderCommandHandler;
use app\common\Core\Application\UseCases\Commands\CreateOrder\CreateOrderCommandHandlerInterface;
use app\common\Core\Application\UseCases\Commands\MoveCouriers\MoveCouriersCommandHandler;
use app\common\Core\Application\UseCases\Commands\MoveCouriers\MoveCouriersCommandHandlerInterface;
use app\common\Core\Application\UseCases\Queries\GetAllCouriers\GetAllCouriersQueryHandler;
use app\common\Core\Application\UseCases\Queries\GetAllCouriers\GetAllCouriersQueryHandlerInterface;
use app\common\Core\Application\UseCases\Queries\GetBusyCouriers\GetBusyCouriersQueryHandler;
use app\common\Core\Application\UseCases\Queries\GetBusyCouriers\GetBusyCouriersQueryHandlerInterface;
use app\common\Core\Application\UseCases\Queries\GetUncompletedOrders\GetUncompletedOrdersQueryHandler;
use app\common\Core\Application\UseCases\Queries\GetUncompletedOrders\GetUncompletedOrdersQueryHandlerInterface;
use app\common\Core\Domain\OrderAggregate\Events\OrderCompletedEvent;
use app\common\Core\Domain\Services\Dispatch\DispatchService;
use app\common\Core\Domain\Services\Dispatch\DispatchServiceInterface;
use app\common\Core\Ports\CourierRepositoryInterface;
use app\common\Core\Ports\MessageBusProducerInterface;
use app\common\Core\Ports\OrderRepositoryInterface;
use app\common\Infrastructure\Adapters\Grpc\GeoService\GeoService;
use app\common\Infrastructure\Adapters\Grpc\GeoService\GeoServiceInterface;
use app\common\Infrastructure\Adapters\Kafka\OrderCompleted\MessageBusProducer;
use app\common\Infrastructure\Adapters\Postgres\DomainEventsDispatcher;
use app\common\Infrastructure\Adapters\Postgres\Repositories\CourierRepository;
use app\common\Infrastructure\Adapters\Postgres\Repositories\OrderRepository;
use common\Infrastructure\Adapters\Postgres\DomainEventsDispatcherInterface;
use common\Infrastructure\Adapters\Postgres\UnitOfWork;
use common\Infrastructure\Adapters\Postgres\UnitOfWorkInterface;
use Grpc\ChannelCredentials;
use Rdkafka\Conf;
use RdKafka\Consumer;
use RdKafka\Producer;
use RdKafka\TopicConf;
use yii\di\Container;

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
        UnitOfWorkInterface::class => function (Container $container) {
            return new UnitOfWork(
                connection: Yii::$app->db,
                domainEventsDispatcher: $container->get(DomainEventsDispatcherInterface::class),
            );
        },
        GetAllCouriersQueryHandlerInterface::class => GetAllCouriersQueryHandler::class,
        GeoServiceInterface::class => function () {
            return new GeoService(
                host: 'busket-geo-1:5004',
                options: [
                    'credentials' => ChannelCredentials::createInsecure()
                ],
            );
        },
        'kafka.basket.consumer' => function (Container $container) {
            $conf = new Conf();
            $conf->set('group.id', 'myConsumerGroup');

            $kafka = new Consumer($conf);
            $kafka->addBrokers('kafka:19092');

            return $kafka;
        },
        'kafka.notification.producer' => function (Container $container) {
            $conf = new Conf();

            $kafka = new Producer($conf);
            $kafka->addBrokers('kafka:19092');

            return $kafka;
        },
        MessageBusProducerInterface::class => MessageBusProducer::class,
    ],
    'singletons' => [
        DomainEventsDispatcherInterface::class => function () {
            return new DomainEventsDispatcher(
                domainHandlersMap: [
                    OrderCompletedEvent::class => OrderCompletedDomainEventHandler::class,
                ],
            );
        },
    ]
];