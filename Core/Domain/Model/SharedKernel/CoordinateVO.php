<?php

declare(strict_types=1);

namespace Core\Domain\Model\SharedKernel;

use Webmozart\Assert\Assert;

final readonly class CoordinateVO
{
    private const MIN_VALUE = 1;
    private const MAX_VALUE = 10;

    public function __construct(
        private int $value,
    ) {
        Assert::range(
            value: $this->value,
            min: self::MIN_VALUE,
            max: self::MAX_VALUE,
            message: "Available range for coordinate from 1 to 10. Value is $this->value"
        );
    }

    public function getValue(): int
    {
        return $this->value;
    }
}