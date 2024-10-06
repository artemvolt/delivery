<?php

declare(strict_types=1);

namespace app\common\Api\Adapters\Kafka;

use app\common\Core\Application\UseCases\Commands\CreateOrder\CreateOrderCommandDto;
use app\common\Core\Application\UseCases\Commands\CreateOrder\CreateOrderCommandHandlerInterface;
use Ramsey\Uuid\UuidFactory;
use yii\helpers\Json;

final class BasketConfirmedConsumer
{
    public function __construct(
        private readonly CreateOrderCommandHandlerInterface $createOrderCommandHandler,
        private readonly UuidFactory $uuidFactory,
    ) {
    }

    public function consume(string $message): void
    {
        $message = Json::decode($message);
        $basketUUid = $message['BasketId'] ?? "";
        $street = $message['Address']['Street'] ?? "";

        $this->createOrderCommandHandler->handle(
            new CreateOrderCommandDto(
                basketId: $this->uuidFactory->fromString($basketUUid),
                street: $street,
            )
        );
    }
}