<?php

declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetAllCouriers;

final class LocationDto
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
    ) {
    }
}