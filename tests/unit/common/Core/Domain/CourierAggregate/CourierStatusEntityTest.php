<?php

declare(strict_types=1);

namespace app\tests\unit\common\Core\Domain\CourierAggregate;

use app\common\Core\Domain\CourierAggregate\CourierStatusEntity;
use app\common\Core\Domain\CourierAggregate\CourierStatusEnum;
use Codeception\Test\Unit;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CourierStatusEntityTest extends Unit
{
    public function testFree()
    {
        $this->assertEquals(
            CourierStatusEnum::free->value,
            CourierStatusEntity::free()->getId(),
        );
    }

    public function testCorrectFromId()
    {
        $this->expectNotToPerformAssertions();
        CourierStatusEntity::fromId(CourierStatusEnum::free->value);
        CourierStatusEntity::fromId(CourierStatusEnum::busy->value);
    }

    public function testInvalidFromId()
    {
        $this->expectException(InvalidArgumentException::class);
        CourierStatusEntity::fromId(10);
    }

    public function testBusy()
    {
        $this->assertEquals(
            CourierStatusEnum::busy->value,
            CourierStatusEntity::busy()->getId(),
        );
    }
}
