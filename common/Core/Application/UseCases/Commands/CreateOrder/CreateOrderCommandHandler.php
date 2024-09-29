<?php
declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Commands\CreateOrder;

use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use app\common\Core\Domain\OrderAggregate\OrderAggregate;
use app\common\Core\Ports\OrderRepositoryInterface;

final class CreateOrderCommandHandler implements CreateOrderCommandHandlerInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
    }

    public function handle(CreateOrderCommandDto $createOrderCommandDto): void
    {
        $this->orderRepository->addOrder(
            OrderAggregate::create(
                id: $createOrderCommandDto->basketId,
                location: LocationVO::random(),
            )
        );
    }
}