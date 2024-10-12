<?php
declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Postgres\BackgroundJobs\DomainEventsPublish;

use app\common\Infrastructure\Adapters\Postgres\Models\OutboxDomainEvent;
use app\common\Infrastructure\Exceptions\InfrastructureException;
use app\common\Infrastructure\Exceptions\InfrastructureExceptionInterface;
use Yii;
use yii\queue\interfaces\StatisticsProviderInterface;

final class DomainEventsPublisher
{
    private const TOPIC_NAME = 'domain.events';

    public function __construct(
        private readonly StatisticsProviderInterface $queue,
        private readonly array $domainEventsHandlersMap = [],
    ) {
    }

    /**
     * @throws InfrastructureExceptionInterface
     */
    public function publish(): void
    {
        /**
         * @var OutboxDomainEvent[] $events
         */
        $events = OutboxDomainEvent::find()->andWhere(['status' => OutboxDomainEvent::STATUS_NEW])->all();
        foreach ($events as $event) {
            $handlerClass = $this->domainEventsHandlersMap[$event->event_class] ?? null;
            if (null === $handlerClass) {
                $this->performedEvent($event);
                continue;
            }

            $job = new DomainEventJob();
            $job->eventClass = $event->event_class;
            $job->eventBody = $event->event_body;
            $job->eventHandlerClass = $handlerClass;
            $this->queue->push($job);
            $this->performedEvent($event);
        }
    }

    /**
     * @throws InfrastructureExceptionInterface
     */
    private function performedEvent(OutboxDomainEvent $event)
    {
        $event->status = OutboxDomainEvent::STATUS_PERFORMED;
        if (!$event->save()) {
            throw new InfrastructureException(
                "Could not update outbox event row: "
                . implode(", ", $event->getFirstErrors())
            );
        }
    }
}