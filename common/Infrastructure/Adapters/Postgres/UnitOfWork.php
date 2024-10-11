<?php
declare(strict_types=1);

namespace common\Infrastructure\Adapters\Postgres;

use yii\db\Connection;

final class UnitOfWork implements UnitOfWorkInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly DomainEventsDispatcherInterface $domainEventsDispatcher,
    ) {
    }

    public function transaction(callable $function): mixed
    {
        $this->connection->transaction($function);
        $this->domainEventsDispatcher->publishDomainEvents();
    }
}