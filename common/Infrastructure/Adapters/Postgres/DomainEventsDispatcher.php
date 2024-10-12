<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Postgres;

use app\common\Core\Domain\Aggregates\AggregateInterface;
use app\common\Core\Domain\Events\DomainEventInterface;
use app\common\Infrastructure\Exceptions\InfrastructureException;
use app\common\Infrastructure\Adapters\Postgres\DomainEventsDispatcherInterface;
use Yii;
use yii\base\InvalidConfigException;

final class DomainEventsDispatcher implements DomainEventsDispatcherInterface
{
    private array $events = [];

    public function __construct(
        private readonly array $domainHandlersMap,
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
        foreach ($this->events as $key => $event) {
            $class = get_class($event);
            $handler = $this->domainHandlersMap[$class] ?? null;
            if (null === $handler) {
                continue;
            }

            try {
                $handlerObject = Yii::$container->get($handler);
                $handlerObject->handle($event);
            } catch (InvalidConfigException $e) {
                throw new InfrastructureException($e->getMessage());
            }

            unset($this->events[$key]);
        }
    }
}