<?php

declare(strict_types=1);

namespace app\commands;

use app\common\Api\Adapters\Kafka\BasketConfirmed\BasketConfirmedConsumer;
use RdKafka\Conf;
use RdKafka\Consumer;
use Yii;
use yii\console\Controller;

final class KafkaConsumerController extends Controller
{
    const KAFKA_PARTITION = 0;
    const KAFKA_TOPIC_TEST = 'basket.confirmed';

    public function __construct(
        $id,
        $module,
        private readonly BasketConfirmedConsumer $basketConfirmedConsumer,
        $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $kafka = Yii::$container->get('kafka.basket.consumer');

        $topicConf = new \RdKafka\TopicConf();
        $topicConf->set('offset.store.method', 'broker');
        $topicConf->set('auto.offset.reset', 'smallest');

        $topic = $kafka->newTopic(self::KAFKA_TOPIC_TEST, $topicConf);

        $topic->consumeStart(self::KAFKA_PARTITION, RD_KAFKA_OFFSET_STORED);

        while (true) {
            $message = $topic->consume(self::KAFKA_PARTITION, 120*10000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $this->stdout('Payload: ' . $message->payload) . PHP_EOL;
                    $this->basketConfirmedConsumer->consume($message->payload);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    $this->stdout('No more messages; will wait for more') . PHP_EOL;
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    $this->stdout('Timed out') . PHP_EOL;
                    break;
                default:
                    $this->stdout($message->errstr() . ' - ' . $message->err) . PHP_EOL;
                    throw new \Exception($message->errstr(), $message->err);
                    break;
            }
        }
    }
}