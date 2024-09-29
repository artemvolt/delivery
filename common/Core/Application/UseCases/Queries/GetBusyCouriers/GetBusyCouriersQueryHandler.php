<?php
declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetBusyCouriers;

use app\common\Core\Domain\CourierAggregate\CourierAggregate;
use app\common\Core\Ports\CourierRepositoryInterface;

final class GetBusyCouriersQueryHandler implements GetBusyCouriersQueryHandlerInterface
{
    public function __construct(
        private readonly CourierRepositoryInterface $courierRepository,
    ) {
    }

    public function handle(GetBusyCouriersQueryDto $getFreeCouriersCommandDto): GetBusyCouriersResponse
    {
        return new GetBusyCouriersResponse(
            couriers: array_map(
                function (CourierAggregate $courierAggregate) {
                    return new CourierDto(
                        id: $courierAggregate->getId(),
                        name: $courierAggregate->getName(),
                        location: new LocationDto(
                            x: $courierAggregate->getLocation()->getX()->getValue(),
                            y: $courierAggregate->getLocation()->getY()->getValue(),
                        ),
                        transportId: $courierAggregate->getTransport()->getId(),
                    );
                },
                $this->courierRepository->getBusyCouriers(),
            )
        );
    }
}