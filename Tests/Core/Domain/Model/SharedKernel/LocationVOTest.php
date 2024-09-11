<?php

declare(strict_types=1);

namespace Tests\Core\Domain\Model\SharedKernel;

use Codeception\Test\Unit;
use Core\Domain\Model\SharedKernel\CoordinateVO;
use Core\Domain\Model\SharedKernel\LocationVO;
use Tests\Support\UnitTester;

class LocationVOTest extends Unit
{
    protected UnitTester $tester;

    public function testEqual()
    {
        $locationOne = new LocationVO(
            x: new CoordinateVO(5),
            y: new CoordinateVO(6)
        );
        $locationTwo = new LocationVO(
            x: new CoordinateVO(5),
            y: new CoordinateVO(6)
        );
        $this->tester->assertTrue($locationOne->isEqual($locationTwo));
    }

    public function testNotEqual()
    {
        $locationOne = new LocationVO(
            x: new CoordinateVO(7),
            y: new CoordinateVO(6)
        );
        $locationTwo = new LocationVO(
            x: new CoordinateVO(5),
            y: new CoordinateVO(6)
        );
        $this->tester->assertFalse($locationOne->isEqual($locationTwo));
    }

    public function testNotEqualSymmetrical()
    {
        $locationOne = new LocationVO(
            x: new CoordinateVO(7),
            y: new CoordinateVO(6)
        );
        $locationTwo = new LocationVO(
            x: new CoordinateVO(6),
            y: new CoordinateVO(7)
        );
        $this->tester->assertFalse($locationOne->isEqual($locationTwo));
    }

    public function testRandom()
    {
        $random = LocationVO::random();
        $this->tester->assertIsObject($random);
    }
}
