<?php

declare(strict_types=1);

namespace Tests\Unit\Core\Domain\Model\SharedKernel;

use Core\Domain\Model\SharedKernel\CoordinateVO;
use Tests\Support\UnitTester;

class CoordinateVOTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    public function tesNegativeValue()
    {
        $this->expectExceptionMessage("Available range for coordinate from 1 to 10. Value is -1");
        $location = new CoordinateVO(-1);
    }

    public function testMinimalCorrectValue()
    {
        $this->expectNotToPerformAssertions();
        $location = new CoordinateVO(1);
    }

    public function testCorrectValue()
    {
        $this->expectNotToPerformAssertions();
        $location = new CoordinateVO(5);
    }

    public function testMaximumCorrectValue()
    {
        $this->expectNotToPerformAssertions();
        $location = new CoordinateVO(10);
    }

    public function testMaximumInCorrectValue()
    {
        $this->expectExceptionMessage("Available range for coordinate from 1 to 10. Value is 11");
        $location = new CoordinateVO(11);
    }
}
