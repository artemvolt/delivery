<?php

declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetBusyCouriers;

interface GetBusyCouriersQueryHandlerInterface
{
    public function handle(GetBusyCouriersQueryDto $getFreeCouriersCommandDto): GetBusyCouriersResponse;
}