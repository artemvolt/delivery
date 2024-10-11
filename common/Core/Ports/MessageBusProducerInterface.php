<?php

declare(strict_types=1);

namespace app\common\Core\Ports;

use app\common\Core\Domain\OrderAggregate\Events\OrderCompletedEvent;

interface MessageBusProducerInterface
{
    public function publish(OrderCompletedEvent $orderCompletedEvent): void;
}