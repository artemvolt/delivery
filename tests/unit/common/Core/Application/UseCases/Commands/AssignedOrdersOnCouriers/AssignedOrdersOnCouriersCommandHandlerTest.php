<?php

declare(strict_types=1);

namespace Unit\common\Core\Application\UseCases\Commands\AssignedOrdersOnCouriers;

use app\common\Core\Application\UseCases\Commands\AssignedOrdersOnCouriers\AssignedOrdersOnCouriersCommandDto;
use app\common\Core\Application\UseCases\Commands\AssignedOrdersOnCouriers\AssignedOrdersOnCouriersCommandHandler;
use app\common\Core\Domain\CourierAggregate\CourierAggregate;
use app\common\Core\Domain\CourierAggregate\TransportEntity;
use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use app\common\Core\Domain\OrderAggregate\OrderAggregate;
use app\common\Core\Domain\Services\Dispatch\DispatchService;
use app\common\Core\Domain\Services\Dispatch\DispatchServiceInterface;
use app\common\Core\Ports\CourierRepositoryInterface;
use app\common\Core\Ports\OrderRepositoryInterface;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use DomainException;
use Ramsey\Uuid\UuidFactory;
use \UnitTester;

class AssignedOrdersOnCouriersCommandHandlerTest extends Unit
{
    public function testEmptyOrders()
    {
        $orderRepository = Stub::makeEmpty(OrderRepositoryInterface::class);
        $orderRepository->method('getCreatedOrders')->willReturn([]);

        $handler = new AssignedOrdersOnCouriersCommandHandler(
            orderRepository: $orderRepository,
            courierRepository: $this->makeEmpty(CourierRepositoryInterface::class),
            dispatchCommandService: $this->makeEmpty(DispatchServiceInterface::class)
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Orders are empty");
        $handler->handle(new AssignedOrdersOnCouriersCommandDto());
    }

    public function testEmptyCouriers()
    {
        $uuidFactory = new UuidFactory();
        $orderRepository = Stub::makeEmpty(OrderRepositoryInterface::class);
        $orderRepository->method('getCreatedOrders')->willReturn([
            OrderAggregate::create($uuidFactory->uuid7(), LocationVO::random()),
        ]);

        $courierRepository = $this->makeEmpty(CourierRepositoryInterface::class);
        $orderRepository->expects(Expected::once()->getMatcher())->method('getCreatedOrders')->willReturn([]);

        $handler = new AssignedOrdersOnCouriersCommandHandler(
            orderRepository: $orderRepository,
            courierRepository: $courierRepository,
            dispatchCommandService: new DispatchService(),
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Couriers are expected");
        $handler->handle(new AssignedOrdersOnCouriersCommandDto());
    }

    public function testAssign()
    {
        $uuidFactory = new UuidFactory();

        $courier = CourierAggregate::create(
            "test",
            TransportEntity::car(),
            LocationVO::random()
        );

        $firstUuid = $uuidFactory->uuid7();

        $orderRepository = Stub::makeEmpty(OrderRepositoryInterface::class, [
            'getCreatedOrders' => fn () => [
                OrderAggregate::create($firstUuid, LocationVO::random()),
                OrderAggregate::create($uuidFactory->uuid7(), LocationVO::random()),
            ],
            'updateOrder' => function (OrderAggregate $orderAggregate) use ($courier, $firstUuid) {
                $this->assertEquals($firstUuid->toString(), $orderAggregate->getId()->toString());
                $this->assertEquals($courier->getId(), $orderAggregate->getCourierId());
            }
        ]);
        $orderRepository->expects(Expected::once()->getMatcher())->method('getCreatedOrders');
        $orderRepository->expects(Expected::once()->getMatcher())->method('updateOrder');

        $courierRepository = $this->makeEmpty(CourierRepositoryInterface::class);
        $courierRepository->expects(Expected::once()->getMatcher())->method('getFreeCouriers')->willReturn([
            $courier
        ]);

        $dispatchService = $this->makeEmpty(DispatchServiceInterface::class);
        $dispatchService->expects(Expected::once()->getMatcher())->method('getBestCourierForAssign')->willReturn(
            $courier
        );

        $handler = new AssignedOrdersOnCouriersCommandHandler(
            orderRepository: $orderRepository,
            courierRepository: $courierRepository,
            dispatchCommandService: $dispatchService,
        );

        $handler->handle(new AssignedOrdersOnCouriersCommandDto());
    }
}
