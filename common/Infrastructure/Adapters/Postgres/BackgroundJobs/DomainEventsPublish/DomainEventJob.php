<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Postgres\BackgroundJobs\DomainEventsPublish;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

final class DomainEventJob extends BaseObject implements JobInterface
{
    public $eventClass;
    public $eventBody;
    public $eventHandlerClass;

    public function execute($queue)
    {
        $event = unserialize(base64_decode($this->eventBody));
        $handlerClass = $this->eventHandlerClass;
        $handler = Yii::$container->get($handlerClass);
        $handler->handle($event);
    }
}