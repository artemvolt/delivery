<?php

declare(strict_types=1);

namespace Core\Domain\Model\SharedKernel;

final readonly class DistanceVO
{
    public function __construct(
        private LocationVO $locationOne,
        private LocationVO $locationTwo
    ) {
    }

    public function getX(): int
    {
        return abs(
            $this->locationOne->x->value
                 - $this->locationTwo->x->value
        );
    }

    public function getY(): int
    {
        return abs(
            $this->locationOne->y->value
                 - $this->locationTwo->y->value
        );
    }
}