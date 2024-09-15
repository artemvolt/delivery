<?php

declare(strict_types=1);

namespace Tests\Core\Domain\CourierAggregate;

use Core\Domain\CourierAggregate\TransportEntity;
use Core\Domain\CourierAggregate\TransportEntityEnum;
use PHPUnit\Framework\TestCase;

class TransportEntityTest extends TestCase
{
    public function testPedestrian()
    {
        $this->assertEquals(1, TransportEntity::pedestrian()->getSpeed()->getValue());
    }

    public function testCar()
    {
        $this->assertEquals(2, TransportEntity::bicycle()->getSpeed()->getValue());
    }

    public function testBicycle()
    {
        $this->assertEquals(3, TransportEntity::car()->getSpeed()->getValue());
    }

    public function testFromNameSuccess()
    {
        $this->expectNotToPerformAssertions();
        TransportEntity::fromName(TransportEntityEnum::pedestrian->name);
        TransportEntity::fromName(TransportEntityEnum::bicycle->name);
        TransportEntity::fromName(TransportEntityEnum::car->name);
    }

    public function testFromNameInvalid()
    {
        $this->expectExceptionMessage('Unknown name of transport: unknown');
        TransportEntity::fromName('unknown');
    }

    public function testIsEqual()
    {
        $this->assertTrue(
            TransportEntity::pedestrian()->isEqual(
                TransportEntity::fromName(TransportEntityEnum::pedestrian->name)
            )
        );
        $this->assertTrue(
            TransportEntity::bicycle()->isEqual(
                TransportEntity::fromName(TransportEntityEnum::bicycle->name)
            )
        );
        $this->assertTrue(
            TransportEntity::car()->isEqual(
                TransportEntity::fromName(TransportEntityEnum::car->name)
            )
        );
        $this->assertFalse(
            TransportEntity::car()->isEqual(
                TransportEntity::fromName(TransportEntityEnum::bicycle->name)
            )
        );
    }
}
