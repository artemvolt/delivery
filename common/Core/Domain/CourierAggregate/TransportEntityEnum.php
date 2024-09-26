<?php

declare(strict_types=1);

namespace app\common\Core\Domain\CourierAggregate;

enum TransportEntityEnum: int
{
    case pedestrian = 1;
    case bicycle = 2;
    case car = 3;
}
