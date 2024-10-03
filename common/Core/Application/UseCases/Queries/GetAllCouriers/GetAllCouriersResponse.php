<?php
declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetAllCouriers;

final class GetAllCouriersResponse
{
    /**
     * @param CourierDto[] $couriers
     */
    public function __construct(
        public readonly array $couriers,
    ) {
    }
}