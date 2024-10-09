<?php

declare(strict_types=1);

namespace common\Infrastructure\Adapters\Postgres;

use app\common\Core\Domain\Events\DomainEventInterface;
use app\common\Infrastructure\Exceptions\InfrastructureExceptionInterface;

interface DomainEventsDispatcherInterface
{
    public function store(DomainEventInterface $event): void;

    /**
     * @throws InfrastructureExceptionInterface
     */
    public function publishDomainEvents(): void;
}