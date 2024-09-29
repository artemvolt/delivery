<?php
declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetUncompletedOrders;

use app\common\Core\Domain\OrderAggregate\OrderAggregate;
use app\common\Core\Ports\OrderRepositoryInterface;
use yii\helpers\ArrayHelper;

final class GetUncompletedOrdersQueryHandler implements GetUncompletedOrdersQueryHandlerInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
    }

    public function handle(GetUncompletedOrdersQueryDto $getUncompletedOrdersQueryDto): GetUncompletedOrdersQueryResponse
    {
        $uncompletedOrders = ArrayHelper::merge(
            $this->orderRepository->getCreatedOrders(),
            $this->orderRepository->getAssignedOrders(),
        );

        return new GetUncompletedOrdersQueryResponse(
            orders: array_map(
                callback: function (OrderAggregate $orderAggregate) {
                    return new OrderDto(
                        id: $orderAggregate->getId(),
                        location: new LocationDto(
                            x: $orderAggregate->getLocation()->getX()->getValue(),
                            y: $orderAggregate->getLocation()->getY()->getValue(),
                        )
                    );
                },
                array: $uncompletedOrders,
            )
        );
    }
}