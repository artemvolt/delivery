<?php

declare(strict_types=1);

namespace Core\Domain\Model\SharedKernel;

final readonly class LocationVO
{
    public function __construct(
        private CoordinateVO $x,
        private CoordinateVO $y,
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
        return $this->x->getValue() === $location->x->getValue() && $this->y->getValue() === $location->y->getValue();
    }

    public function getX(): CoordinateVO
    {
        return $this->x;
    }

    public function getY(): CoordinateVO
    {
        return $this->y;
    }
}