<?php
declare(strict_types=1);

namespace app\common\Core\Application\DomainEventHandlers\OrderCompleted;

use app\common\Core\Domain\OrderAggregate\Events\OrderCompletedEvent;
use app\common\Core\Ports\MessageBusProducerInterface;

final class OrderCompletedDomainEventHandler
{
    public function __construct(
        private readonly MessageBusProducerInterface $messageBusProducer,
    ) {
    }

    public function handle(OrderCompletedEvent $orderCompletedEvent): void
    {
        $this->messageBusProducer->publish($orderCompletedEvent);
    }
}