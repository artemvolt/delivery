<?php

namespace app\tests\unit\common\Core\Domain\OrderAggregate;

use app\common\Core\Domain\CourierAggregate\CourierAggregate;
use app\common\Core\Domain\CourierAggregate\TransportEntity;
use app\common\Core\Domain\Model\SharedKernel\CoordinateVO;
use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use app\common\Core\Domain\OrderAggregate\OrderAggregate;
use app\common\Core\Domain\OrderAggregate\OrderStatusEntity;
use Codeception\Test\Unit;
use DomainException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidFactory;

class OrderAggregateTest extends Unit
{
    public function testCreate()
    {
        $newOrder = OrderAggregate::create(
            id: 1,
            location: new LocationVO(
                new CoordinateVO(1),
                new CoordinateVO(1),
            )
        );

        $this->assertEquals(1, $newOrder->getId());
        $this->assertEquals(1, $newOrder->getLocation()->getX()->getValue());
        $this->assertEquals(1, $newOrder->getLocation()->getY()->getValue());
        $this->assertTrue($newOrder->getStatus()->isEqual(OrderStatusEntity::created()));
    }

    public function testAssignCourier()
    {
        $uuidFactory = new UuidFactory();
        $uuid = $uuidFactory->uuid7();

        $newOrder = OrderAggregate::create(
            id: 1,
            location: new LocationVO(
                new CoordinateVO(1),
                new CoordinateVO(1),
            )
        );

        $courier = CourierAggregate::create(
            name: "Jonny",
            transport: TransportEntity::pedestrian(),
            location: new LocationVO(
                x: new CoordinateVO(1),
                y: new CoordinateVO(1),
            )
        );

        $newOrder->assignCourier($courier);
        $this->assertEquals($courier->getId()->toString(), $newOrder->getCourierId()->toString());
        $this->assertTrue($newOrder->getStatus()->isEqual(OrderStatusEntity::assigned()));
    }

    public function testCorrectCompleted()
    {
        $newOrder = OrderAggregate::create(
            id: 1,
            location: new LocationVO(
                new CoordinateVO(1),
                new CoordinateVO(1),
            )
        );

        $courier = CourierAggregate::create(
            name: "Jonny",
            transport: TransportEntity::pedestrian(),
            location: new LocationVO(
                x: new CoordinateVO(1),
                y: new CoordinateVO(1),
            )
        );

        $newOrder->assignCourier($courier);
        $newOrder->toCompleted();
        $this->assertTrue($newOrder->getStatus()->isEqual(OrderStatusEntity::completed()));
    }

    public function testInvalidCompleted()
    {
        $newOrder = OrderAggregate::create(
            id: 1,
            location: new LocationVO(
                new CoordinateVO(1),
                new CoordinateVO(1),
            )
        );
        $this->expectException(DomainException::class);
        $newOrder->toCompleted();
    }
}
