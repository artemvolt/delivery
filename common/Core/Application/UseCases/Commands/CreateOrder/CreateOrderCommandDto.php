<?php

declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Commands\CreateOrder;

final class CreateOrderCommandDto
{
    public function __construct(
        public readonly int $basketId,
        public readonly string $street,
    ) {
    }
}