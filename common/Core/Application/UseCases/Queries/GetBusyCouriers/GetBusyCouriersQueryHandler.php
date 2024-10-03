<?php
declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetBusyCouriers;

use app\common\Core\Ports\CourierRepositoryInterface;

final class GetBusyCouriersQueryHandler implements GetBusyCouriersQueryHandlerInterface
{
    public function __construct(
        private readonly CourierRepositoryInterface $courierRepository,
    ) {
    }

    public function handle(GetBusyCouriersQueryDto $getFreeCouriersCommandDto): GetBusyCouriersResponse
    {
        $busyCouriers = $this->courierRepository->getBusyCouriers();
        $busyCouriersForResponse = [];

        foreach ($busyCouriers as $busyCourier) {
            $busyCouriersForResponse[] = new CourierDto(
                id: $busyCourier->getId(),
                name: $busyCourier->getName(),
                location: new LocationDto(
                    x: $busyCourier->getLocation()->getX()->getValue(),
                    y: $busyCourier->getLocation()->getY()->getValue(),
                ),
                transportId: $busyCourier->getTransport()->getId(),
            );
        }
        
        return new GetBusyCouriersResponse(
            couriers: $busyCouriersForResponse,
        );
    }
}