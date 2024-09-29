<?php

declare(strict_types=1);

namespace app\common\Core\Domain\Services\Dispatch;

use app\common\Core\Domain\CourierAggregate\CourierAggregate;
use app\common\Core\Domain\OrderAggregate\OrderAggregate;
use Codeception\Util\Debug;
use DomainException;
use yii\helpers\ArrayHelper;

final class DispatchService implements DispatchServiceInterface
{
    public function getBestCourierForAssign(OrderAggregate $order, array $couriers): CourierAggregate
    {
        if (empty($couriers)) {
            throw new DomainException("Couriers are expected");
        }

        $times = [];

        foreach ($couriers as $courier) {
            $times[] = [
                'time' => $courier->calculateTimeToPoint($order->getLocation()),
                'courier' => $courier,
            ];
        }

        ArrayHelper::multisort($times, ['time'], [SORT_ASC]);

        Debug::debug($times);
        return $times[0]['courier'];
    }
}