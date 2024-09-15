<?php

declare(strict_types=1);

namespace Core\Domain\CourierAggregate;

use Webmozart\Assert\Assert;

final class SpeedVO
{
    public function __construct(
        public readonly int $value,
    ) {
        Assert::positiveInteger($this->value, 'Speed must be positive');
    }
}