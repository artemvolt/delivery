<?php

declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetUncompletedOrders;

interface GetUncompletedOrdersQueryHandlerInterface
{
    public function handle(GetUncompletedOrdersQueryDto $getUncompletedOrdersQueryDto): GetUncompletedOrdersQueryResponse;
}