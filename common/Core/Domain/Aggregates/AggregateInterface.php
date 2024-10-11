<?php

declare(strict_types=1);

namespace app\common\Core\Domain\Aggregates;

use app\common\Core\Domain\Events\DomainEventInterface;

interface AggregateInterface
{
    /**
     * post: Get events and clear them
     * @return DomainEventInterface[]
     */
    public function pullEvents(): array;
}