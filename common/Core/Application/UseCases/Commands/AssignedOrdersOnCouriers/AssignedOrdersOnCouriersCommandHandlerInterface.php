<?php

declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Commands\AssignedOrdersOnCouriers;

use DomainException;

interface AssignedOrdersOnCouriersCommandHandlerInterface
{
    /**
     * @throws DomainException
     */
    public function handle(AssignedOrdersOnCouriersCommandDto $dto): void;
}