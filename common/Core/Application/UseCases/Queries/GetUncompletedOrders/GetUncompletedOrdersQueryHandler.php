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

        $ordersForResponse = [];

        foreach ($uncompletedOrders as $uncompletedOrder) {
            $ordersForResponse[] = new OrderDto(
                id: $uncompletedOrder->getId(),
                location: new LocationDto(
                    x: $uncompletedOrder->getLocation()->getX()->getValue(),
                    y: $uncompletedOrder->getLocation()->getY()->getValue(),
                )
            );
        }

        return new GetUncompletedOrdersQueryResponse(
            orders: $ordersForResponse,
        );
    }
}