<?php

declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Queries\GetAllCouriers;

interface GetAllCouriersQueryHandlerInterface
{
    public function handle(GetAllCouriersQueryDto $getFreeCouriersCommandDto): GetAllCouriersResponse;
}