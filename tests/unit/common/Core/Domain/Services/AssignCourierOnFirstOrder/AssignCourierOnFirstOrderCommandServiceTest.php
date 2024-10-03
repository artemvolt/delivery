<?php

declare(strict_types=1);

namespace app\tests\unit\common\Core\Domain\Services\AssignCourierOnFirstOrder;

use app\common\Core\Domain\CourierAggregate\CourierAggregate;
use app\common\Core\Domain\CourierAggregate\TransportEntity;
use app\common\Core\Domain\CourierAggregate\TransportEntityEnum;
use app\common\Core\Domain\Model\SharedKernel\CoordinateVO;
use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use app\common\Core\Domain\OrderAggregate\OrderAggregate;
use app\common\Core\Domain\Services\Dispatch\DispatchService;
use Codeception\Test\Unit;

class AssignCourierOnFirstOrderCommandServiceTest extends Unit
{
    public static function assignDataProvider(): array
    {
        return [
            'all' => [
                [
                    ["1", TransportEntityEnum::pedestrian, 1, 1],
                    ["2", TransportEntityEnum::bicycle, 1, 1],
                    ["3", TransportEntityEnum::car, 1, 1],
                ],
                "3"
            ],
            'without car' => [
                [
                    ["1", TransportEntityEnum::pedestrian, 1, 1],
                    ["2", TransportEntityEnum::bicycle, 1, 1],
                ],
                "2"
            ],
            'only pedestrian' => [
                [
                    ["1", TransportEntityEnum::pedestrian, 1, 1],
                ],
                "1"
            ],
            'all cars' => [
                [
                    ["1", TransportEntityEnum::car, 1, 1],
                    ["2", TransportEntityEnum::car, 3, 4],
                    ["3", TransportEntityEnum::car, 1, 5],
                ],
                "2"
            ],
        ];
    }

    /**
     * @dataProvider assignDataProvider
     */
    public function testAssign(array $couriers, string $expectedWinnerName)
    {
        $couriersEntities = array_map(
            function (array $courier) {
                return CourierAggregate::create(
                    name: $courier[0],
                    transport: TransportEntity::fromName($courier[1]->name),
                    location: new LocationVO(
                        x: new CoordinateVO($courier[2]),
                        y: new CoordinateVO($courier[3]),
                    ),
                );
            },
            $couriers
        );

        $service = new DispatchService();
        $winnerCourier = $service->getBestCourierForAssign(
            order: OrderAggregate::create(
                id: 1,
                location: new LocationVO(
                    x: new CoordinateVO(5),
                    y: new CoordinateVO(5),
                )
            ),
            couriers: $couriersEntities,
        );
        $this->assertEquals($expectedWinnerName, $winnerCourier->getName());
    }
}
