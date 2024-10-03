<?php
declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetBusyCouriers;

final class GetBusyCouriersResponse
{
    /**
     * @param CourierDto[] $couriers
     */
    public function __construct(
        public readonly array $couriers,
    ) {
    }
}