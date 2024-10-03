<?php
declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetAllCouriers;

use app\common\Core\Ports\CourierRepositoryInterface;

final class GetAllCouriersQueryHandler implements GetAllCouriersQueryHandlerInterface
{
    public function __construct(
        private readonly CourierRepositoryInterface $courierRepository,
    ) {
    }

    public function handle(GetAllCouriersQueryDto $getFreeCouriersCommandDto): GetAllCouriersResponse
    {
        $busyCouriers = $this->courierRepository->getAllCouriers();
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
        
        return new GetAllCouriersResponse(
            couriers: $busyCouriersForResponse,
        );
    }
}