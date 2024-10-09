<?php

declare(strict_types=1);

namespace common\Infrastructure\Adapters\Postgres;

interface UnitOfWorkInterface
{
    public function transaction(callable $function): mixed;
}