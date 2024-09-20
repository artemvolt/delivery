<?php

declare(strict_types=1);

namespace Core\Ports;

use Codeception\Exception\ContentNotFound;
use Core\Domain\OrderAggregate\OrderAggregate;
use DomainException;

interface OrderRepositoryInterface
{
    /**
     * @throws DomainException
     */
    public function addOrder(OrderAggregate $order): void;

    /**
     * @throws DomainException
     * @throws ContentNotFound
     */
    public function updateOrder(OrderAggregate $order): void;

    /**
     * @throws DomainException
     * @throws ContentNotFound
     */
    public function getById(int $orderId): OrderAggregate;

    /**
     * @throws ContentNotFound
     */
    public function getCreatedOrders(): array;

    /**
     * @throws ContentNotFound
     */
    public function getAssignedOrders(): array;
}