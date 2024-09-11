<?php

namespace Tests\Core\Domain\OrderAggregate;

use Core\Domain\OrderAggregate\OrderStatusEntity;
use Core\Domain\OrderAggregate\OrderStatusEnum;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class OrderStatusEntityTest extends TestCase
{
    public function testCorrectFromId()
    {
        $this->expectNotToPerformAssertions();
        OrderStatusEntity::fromId(OrderStatusEnum::created->value);
        OrderStatusEntity::fromId(OrderStatusEnum::assigned->value);
        OrderStatusEntity::fromId(OrderStatusEnum::completed->value);
    }

    public function testInvalidFromId()
    {
        $this->expectException(InvalidArgumentException::class);
        OrderStatusEntity::fromId(10);
    }

    public function testAssigned()
    {
        $this->assertEquals(
            OrderStatusEnum::assigned->value,
            OrderStatusEntity::assigned()->getId()
        );
    }

    public function testCreated()
    {
        $this->assertEquals(
            OrderStatusEnum::created->value,
            OrderStatusEntity::created()->getId()
        );
    }

    public function testCorrectFromName()
    {
        $this->expectNotToPerformAssertions();
        OrderStatusEntity::fromName(OrderStatusEnum::created->name);
        OrderStatusEntity::fromName(OrderStatusEnum::assigned->name);
        OrderStatusEntity::fromName(OrderStatusEnum::completed->name);
    }

    public function testInValidFromName()
    {
        $this->expectException(InvalidArgumentException::class);
        OrderStatusEntity::fromName('unknown');
    }

    public function testCompleted()
    {
        $this->assertEquals(
            OrderStatusEnum::completed->value,
            OrderStatusEntity::completed()->getId()
        );
    }
}
