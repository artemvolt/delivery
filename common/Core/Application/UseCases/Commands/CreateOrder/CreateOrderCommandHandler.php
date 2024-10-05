<?php
declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Commands\CreateOrder;

use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use app\common\Core\Domain\OrderAggregate\OrderAggregate;
use app\common\Core\Ports\OrderRepositoryInterface;
use app\common\Infrastructure\Adapters\Grpc\GeoService\GeoServiceInterface;
use DomainException;

final class CreateOrderCommandHandler implements CreateOrderCommandHandlerInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly GeoServiceInterface $geoService,
    ) {
    }

    /**
     * @throws DomainException
     */
    public function handle(CreateOrderCommandDto $createOrderCommandDto): void
    {
        $location = $this->geoService->getLocationByStreetName($createOrderCommandDto->street);
        $this->orderRepository->addOrder(
            OrderAggregate::create(
                id: $createOrderCommandDto->basketId,
                location: $location,
            )
        );
    }
}