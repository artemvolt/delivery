<?php

declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Commands\CreateOrder;

use Ramsey\Uuid\UuidInterface;

final class CreateOrderCommandDto
{
    public function __construct(
        public readonly UuidInterface $basketId,
        public readonly string $street,
    ) {
    }
}