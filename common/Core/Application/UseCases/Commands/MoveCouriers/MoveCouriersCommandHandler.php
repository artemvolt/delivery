<?php
declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Commands\MoveCouriers;

use app\common\Core\Ports\CourierRepositoryInterface;
use app\common\Core\Ports\OrderRepositoryInterface;
use Yii;

final class MoveCouriersCommandHandler implements MoveCouriersCommandHandlerInterface
{
    public function __construct(
        private readonly CourierRepositoryInterface $courierRepository,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
    }

    public function handle(MoveCouriersCommandDto $moveCouriersCommandDto): void
    {
        Yii::$app->db->transaction(function () {
            $orders = $this->orderRepository->getAssignedOrders();
            foreach ($orders as $order) {
                $courier = $this->courierRepository->getById($order->getCourierId());
                $courier->move($order->getLocation());
                if ($courier->getLocation()->isEqual($order->getLocation())) {
                    $order->toCompleted();
                    $courier->setFreeStatus();
                    $this->orderRepository->updateOrder($order);
                }
                $this->courierRepository->updateCourier($courier);
            }
        });
    }
}