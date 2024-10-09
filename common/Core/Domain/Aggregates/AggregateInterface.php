<?php

declare(strict_types=1);

namespace app\common\Core\Domain\Aggregates;

use app\common\Core\Domain\Events\DomainEventInterface;

interface AggregateInterface
{
    /**
     * @return DomainEventInterface[]
     */
    public function getEvents(): array;

    public function clearDomainEvents(): void;
}