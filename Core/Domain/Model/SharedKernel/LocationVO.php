<?php

declare(strict_types=1);

namespace Core\Domain\Model\SharedKernel;

final readonly class LocationVO
{
    public function __construct(
        public CoordinateVO $x,
        public CoordinateVO $y,
    ) {
    }

    public static function random(): LocationVO
    {
        $randomX = random_int(1, 10);
        $randomY = random_int(1, 10);

        return new LocationVO(
            x: new CoordinateVO($randomX),
            y: new CoordinateVO($randomY),
        );
    }

    public function isEqual(LocationVO $location): bool
    {
        return $this->x->value === $location->x->value && $this->y->value === $location->y->value;
    }
}