<?php

declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Commands\CreateOrder;

use DomainException;

interface CreateOrderCommandHandlerInterface
{
    /**
     * @throws DomainException
     */
    public function handle(CreateOrderCommandDto $createOrderCommandDto): void;
}