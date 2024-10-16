<?php

declare(strict_types=1);

namespace app\common\Core\Ports;

use app\common\Core\Domain\OrderAggregate\OrderAggregate;
use Codeception\Exception\ContentNotFound;
use DomainException;
use Ramsey\Uuid\UuidInterface;

interface OrderRepositoryInterface
{
    /**
     * @throws DomainException
     */
    public function addOrder(OrderAggregate $order): void;

    /**
     * @throws DomainException
     */
    public function updateOrder(OrderAggregate $order): void;

    public function getById(UuidInterface $orderId): ?OrderAggregate;

    /**
     * @return OrderAggregate[]
     */
    public function getCreatedOrders(): array;

    /**
     * @return OrderAggregate[]
     */
    public function getAssignedOrders(): array;
}