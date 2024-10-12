<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Postgres;

use app\common\Core\Domain\Aggregates\AggregateInterface;
use app\common\Core\Domain\Events\DomainEventInterface;
use app\common\Infrastructure\Exceptions\InfrastructureExceptionInterface;

interface DomainEventsDispatcherInterface
{
    /**
     * @param DomainEventInterface[] $events
     */
    public function store(array $events): void;

    /**
     * @throws InfrastructureExceptionInterface
     */
    public function publishDomainEvents(): void;
}