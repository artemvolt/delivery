<?php

declare(strict_types=1);

namespace app\common\Core\Domain\OrderAggregate\Events;

use app\common\Core\Domain\Events\DomainEventInterface;
use app\common\Core\Domain\OrderAggregate\OrderAggregate;

final class OrderCompletedEvent implements DomainEventInterface
{
    public function __construct(
        public readonly OrderAggregate $order,
    ) {
    }
}