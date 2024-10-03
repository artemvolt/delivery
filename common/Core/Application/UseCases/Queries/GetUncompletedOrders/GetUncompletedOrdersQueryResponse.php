<?php
declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetUncompletedOrders;

final class GetUncompletedOrdersQueryResponse
{
    /**
     * @param OrderDto[] $orders
     */
    public function __construct(
        public readonly array $orders,
    ) {
    }
}