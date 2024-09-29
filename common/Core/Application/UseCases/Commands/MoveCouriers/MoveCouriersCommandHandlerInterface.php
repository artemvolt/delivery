<?php

declare(strict_types=1);

namespace app\common\Core\Application\UseCases\Commands\MoveCouriers;

use DomainException;

interface MoveCouriersCommandHandlerInterface
{
    /**
     * @throws DomainException
     */
    public function handle(MoveCouriersCommandDto $moveCouriersCommandDto): void;
}