<?php

declare(strict_types=1);

namespace Core\Domain\CourierAggregate;

enum CourierStatusEnum: int
{
    case free = 1;
    case busy = 2;
}
