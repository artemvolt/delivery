<?php

declare(strict_types=1);

namespace Infrastructure\Adapters\Postgres\Repositories;

use Core\Domain\CourierAggregate\CourierAggregate;
use Core\Ports\CourierRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

final class CourierRepository implements CourierRepositoryInterface
{
    public function __construct()
    {
    }

    public function addCourier(CourierAggregate $courier): void
    {
        // TODO: Implement addCourier() method.
    }

    public function updateCourier(CourierAggregate $courier): void
    {
        // TODO: Implement updateCourier() method.
    }

    public function getById(UuidInterface $id): CourierAggregate
    {
        // TODO: Implement getById() method.
    }

    public function getFreeCouriers(): array
    {
        // TODO: Implement getFreeCouriers() method.
    }
}