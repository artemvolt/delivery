<?php

declare(strict_types=1);

namespace Infrastructure\Adapters\Postgres\Repositories;

use Core\Domain\OrderAggregate\OrderAggregate;
use Core\Ports\OrderRepositoryInterface;

final class OrderRepository implements OrderRepositoryInterface
{
    public function __construct()
    {
    }

    public function addOrder(OrderAggregate $order): void
    {
        // TODO: Implement addOrder() method.
    }

    public function updateOrder(OrderAggregate $order): void
    {
        // TODO: Implement updateOrder() method.
    }

    public function getById(int $orderId): OrderAggregate
    {
        // TODO: Implement getById() method.
    }

    public function getCreatedOrders(): array
    {
        // TODO: Implement getCreatedOrders() method.
    }

    public function getAssignedOrders(): array
    {
        // TODO: Implement getAssignedOrders() method.
    }
}