<?php
declare(strict_types=1);

namespace common\Infrastructure\Adapters\Postgres;

use app\common\Infrastructure\Adapters\Postgres\DomainEventsDispatcherInterface;
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
        return $this->connection->transaction(function () use ($function) {
            $result = $function();
            $this->domainEventsDispatcher->publishDomainEvents();
            return $result;
        });
    }
}