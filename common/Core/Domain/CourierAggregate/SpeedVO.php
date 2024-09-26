<?php

declare(strict_types=1);

namespace app\common\Core\Domain\CourierAggregate;

use Webmozart\Assert\Assert;

final class SpeedVO
{
    public function __construct(
        private int $value,
    ) {
        Assert::positiveInteger($this->value, 'Speed must be positive');
    }

    public function getValue(): int
    {
        return $this->value;
    }
}