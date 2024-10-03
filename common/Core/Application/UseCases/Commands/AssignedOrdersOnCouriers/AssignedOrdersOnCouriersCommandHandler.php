<?php

declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Commands\AssignedOrdersOnCouriers;

use app\common\Core\Domain\Services\Dispatch\DispatchServiceInterface;
use app\common\Core\Ports\CourierRepositoryInterface;
use app\common\Core\Ports\OrderRepositoryInterface;
use DomainException;

final class AssignedOrdersOnCouriersCommandHandler implements AssignedOrdersOnCouriersCommandHandlerInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CourierRepositoryInterface $courierRepository,
        private readonly DispatchServiceInterface $dispatchCommandService,
    ) {
    }

    public function handle(AssignedOrdersOnCouriersCommandDto $dto): void
    {
        $createdOrders = $this->orderRepository->getCreatedOrders();
        if (empty($createdOrders)) {
            throw new DomainException("Orders are empty");
        }

        $firstCreatedOrder = $createdOrders[0];
        $freeCouriers = $this->courierRepository->getFreeCouriers();
        $bestCourier = $this->dispatchCommandService->getBestCourierForAssign($firstCreatedOrder, $freeCouriers);
        $firstCreatedOrder->assignCourier($bestCourier);
        $this->orderRepository->updateOrder($firstCreatedOrder);
    }
}