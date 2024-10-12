<?php
declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Postgres;

use app\common\Infrastructure\Adapters\Postgres\Models\OutboxDomainEvent;
use app\common\Infrastructure\Exceptions\InfrastructureException;

final class AsyncDomainEventsDispatcher implements DomainEventsDispatcherInterface
{
    public function __construct(
        private array $events = [],
    ) {
    }

    public function store(array $events): void
    {
        foreach ($events as $event) {
            $this->events[] = $event;
        }
    }

    public function publishDomainEvents(): void
    {
        foreach ($this->events as $event) {
            $eventModel = new OutboxDomainEvent();
            $eventModel->event_class = get_class($event);
            $eventModel->event_body = base64_encode(serialize($event));
            $eventModel->status = OutboxDomainEvent::STATUS_NEW;
            if (!$eventModel->save()) {
                throw new InfrastructureException(implode(", ", $eventModel->getFirstErrors()));
            }
        }
    }
}