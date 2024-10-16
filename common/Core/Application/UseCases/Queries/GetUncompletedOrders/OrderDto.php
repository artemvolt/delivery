<?php

declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetUncompletedOrders;

final class OrderDto
{
    public function __construct(
        public readonly string $id,
        public readonly LocationDto $location,
    ) {
    }
}