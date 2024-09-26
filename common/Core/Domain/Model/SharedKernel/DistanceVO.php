<?php

declare(strict_types=1);

namespace app\common\Core\Domain\Model\SharedKernel;

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
            $this->locationOne->getX()->getValue()
                 - $this->locationTwo->getX()->getValue()
        );
    }

    public function getY(): int
    {
        return abs(
            $this->locationOne->getY()->getValue()
                 - $this->locationTwo->getY()->getValue()
        );
    }
}