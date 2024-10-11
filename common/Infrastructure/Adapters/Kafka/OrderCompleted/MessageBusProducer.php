<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Kafka\OrderCompleted;

use app\common\Core\Domain\OrderAggregate\Events\OrderCompletedEvent;
use app\common\Core\Ports\MessageBusProducerInterface;
use Kafka\Produce;
use RuntimeException;
use Yii;
use yii\helpers\Json;

final class MessageBusProducer implements MessageBusProducerInterface
{
    private const TOPIC_NAME = 'order.status.changed';

    public function publish(OrderCompletedEvent $orderCompletedEvent): void
    {
        echo 'publish to message bus' . PHP_EOL;
        $producer = Yii::$container->get('kafka.notification.producer');
        $topic = $producer->newTopic(self::TOPIC_NAME);
        $topic->produce(0, 0, Json::encode([
            'orderId' => $orderCompletedEvent->order->getId()->toString(),
            'orderStatus' => $orderCompletedEvent->order->getStatus()->getId(),
        ]));
        $producer->poll(0);
        $result = $producer->flush(10000);
        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            throw new RuntimeException('Error in broker when flushing: ' . $result);
        }
    }
}