<?php
declare(strict_types=1);

namespace common\Infrastructure\Adapters\Postgres;

use Yii;
use yii\db\Connection;

final class UnitOfWork implements UnitOfWorkInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly array $domainHandlersMap,
    ) {
    }

    public function transaction(callable $function): mixed
    {
        return $this->connection->transaction($function);
    }

    public function publishDomainEvents(array $events): void
    {
        echo 'publish domain events' . PHP_EOL;
        echo 'count: ' . count($events) . PHP_EOL;

        foreach ($events as $event) {
            $class = get_class($event);
            $handler = $this->domainHandlersMap[$class] ?? null;
            if (null === $handler) {
                continue;
            }

            $handlerObject = Yii::$container->get($handler);
            $handlerObject->handle($event);
        }
    }
}