<?php

declare(strict_types=1);

use app\common\Core\Domain\CourierAggregate\CourierAggregate;
use app\common\Core\Domain\CourierAggregate\CourierStatusEntity;
use app\common\Core\Domain\CourierAggregate\TransportEntity;
use app\common\Core\Domain\CourierAggregate\TransportEntityEnum;
use app\common\Core\Domain\Model\SharedKernel\CoordinateVO;
use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use Codeception\Test\Unit;

class CourierAggregateTest extends Unit
{
    public function testCreate()
    {
        $courier = CourierAggregate::create(
            name: "Johny",
            transport: TransportEntity::car(),
            location: new LocationVO(
                x: new CoordinateVO(1),
                y: new CoordinateVO(2),
            ),
        );

        $this->assertEquals("Johny", $courier->getName());
        $this->assertEquals(1, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(2, $courier->getLocation()->getY()->getValue());
        $this->assertEquals(2, $courier->getStatus()->isEqual(CourierStatusEntity::free()));
    }

    public static function timeToPointDataProvider(): array
    {
        return [
            [TransportEntityEnum::pedestrian->name, 1, 1, 8],
            [TransportEntityEnum::bicycle->name, 1, 1, 4],
            [TransportEntityEnum::car->name, 1, 1, 2.7],
            [TransportEntityEnum::car->name, 5, 5, 0],
        ];
    }

    /**
     * @dataProvider timeToPointDataProvider
     */
    public function testCalculateTimeToPointBike(
        string $transportName,
        int $fromX,
        int $fromY,
        float $expectedTime,
    ) {
        $courier = CourierAggregate::create(
            name: "Johny",
            transport: TransportEntity::fromName($transportName),
            location: new LocationVO(
                x: new CoordinateVO($fromX),
                y: new CoordinateVO($fromY),
            ),
        );

        $this->assertEquals($expectedTime, $courier->calculateTimeToPoint(
            new LocationVO(
                x: new CoordinateVO(5),
                y: new CoordinateVO(5),
            )
        ));
    }

    public function testMovePedestrian()
    {
        $courier = CourierAggregate::create(
            name: "Johny",
            transport: TransportEntity::pedestrian(),
            location: new LocationVO(
                x: new CoordinateVO(3),
                y: new CoordinateVO(3),
            ),
        );

        $target = new LocationVO(
            x: new CoordinateVO(1),
            y: new CoordinateVO(5)
        );

        $courier->move($target);
        $this->assertEquals(2, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(3, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(1, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(3, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(1, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(4, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(1, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(5, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(1, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(5, $courier->getLocation()->getY()->getValue());
    }

    public function testMovePedestrianRight()
    {
        $courier = CourierAggregate::create(
            name: "Johny",
            transport: TransportEntity::pedestrian(),
            location: new LocationVO(
                x: new CoordinateVO(3),
                y: new CoordinateVO(3),
            ),
        );

        $target = new LocationVO(
            x: new CoordinateVO(5),
            y: new CoordinateVO(1)
        );

        $courier->move($target);
        $this->assertEquals(4, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(3, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(5, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(3, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(5, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(2, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(5, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(1, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(5, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(1, $courier->getLocation()->getY()->getValue());
    }

    public function testBicycle()
    {
        $courier = CourierAggregate::create(
            name: "Johny",
            transport: TransportEntity::bicycle(),
            location: new LocationVO(
                x: new CoordinateVO(3),
                y: new CoordinateVO(3),
            ),
        );

        $target = new LocationVO(
            x: new CoordinateVO(6),
            y: new CoordinateVO(1)
        );

        $courier->move($target);
        $this->assertEquals(5, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(3, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(6, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(2, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(6, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(1, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(6, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(1, $courier->getLocation()->getY()->getValue());
    }

    public function testCar()
    {
        $courier = CourierAggregate::create(
            name: "Johny",
            transport: TransportEntity::car(),
            location: new LocationVO(
                x: new CoordinateVO(3),
                y: new CoordinateVO(3),
            ),
        );

        $target = new LocationVO(
            x: new CoordinateVO(6),
            y: new CoordinateVO(1)
        );

        $courier->move($target);
        $this->assertEquals(6, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(3, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(6, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(1, $courier->getLocation()->getY()->getValue());

        $courier->move($target);
        $this->assertEquals(6, $courier->getLocation()->getX()->getValue());
        $this->assertEquals(1, $courier->getLocation()->getY()->getValue());
    }
}
