<?php

declare(strict_types=1);

namespace Tests\Core\Domain\Model\SharedKernel;

use Codeception\Test\Unit;
use Core\Domain\Model\SharedKernel\CoordinateVO;
use Core\Domain\Model\SharedKernel\DistanceVO;
use Core\Domain\Model\SharedKernel\LocationVO;
use Tests\Support\UnitTester;

class DistanceLocationVOTest extends Unit
{
    protected UnitTester $tester;

    public function testDistance()
    {
        $locationOne = new LocationVO(
            x: new CoordinateVO(2),
            y: new CoordinateVO(6),
        );
        $locationTwo = new LocationVO(
            x: new CoordinateVO(4),
            y: new CoordinateVO(9),
        );
        $distance = new DistanceVO($locationOne, $locationTwo);

        $this->tester->assertEquals(2, $distance->getX());
        $this->tester->assertEquals(3, $distance->getY());
    }
}
