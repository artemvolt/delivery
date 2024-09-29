<?php

declare(strict_types=1);

namespace app\common\Core\Domain\Services\Dispatch;

use app\common\Core\Domain\CourierAggregate\CourierAggregate;
use app\common\Core\Domain\OrderAggregate\OrderAggregate;
use DomainException;

interface DispatchServiceInterface
{
    /**
     * @param OrderAggregate $order
     * @param CourierAggregate[] $couriers
     * @throws DomainException
     */
    public function getBestCourierForAssign(OrderAggregate $order, array $couriers): CourierAggregate;
}