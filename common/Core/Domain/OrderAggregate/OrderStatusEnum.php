<?php

declare(strict_types=1);

namespace app\common\Core\Domain\OrderAggregate;

enum OrderStatusEnum: int
{
    case created = 1;
    case assigned = 2;
    case completed = 3;
}
