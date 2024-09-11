<?php

declare(strict_types=1);

namespace Core\Domain\Model\SharedKernel;

use Webmozart\Assert\Assert;

final readonly class CoordinateVO
{
    public function __construct(
        public int $value,
    ) {
        Assert::range(
            value: $this->value,
            min: 1,
            max: 10,
            message: "Available range for coordinate from 1 to 10. Value is $this->value"
        );
    }
}