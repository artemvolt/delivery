<?php

declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetAllCouriers;

use Ramsey\Uuid\UuidInterface;

final class CourierDto
{
    public function __construct(
        public readonly UuidInterface $id,
        public readonly string $name,
        public readonly LocationDto $location,
        public readonly int $transportId,
    ) {
    }
}