<?php

declare(strict_types=1);

namespace app\common\Core\Ports;

use app\common\Core\Domain\OrderAggregate\OrderAggregate;
use Codeception\Exception\ContentNotFound;
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