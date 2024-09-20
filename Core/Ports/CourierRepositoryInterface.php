<?php

declare(strict_types=1);

namespace Core\Ports;

use Codeception\Exception\ContentNotFound;
use Core\Domain\CourierAggregate\CourierAggregate;
use DomainException;
use Ramsey\Uuid\UuidInterface;

interface CourierRepositoryInterface
{
    /**
     * @throws DomainException
     */
    public function addCourier(CourierAggregate $courier): void;

    /**
     * @throws DomainException
     */
    public function updateCourier(CourierAggregate $courier): void;

    /**
     * @throws ContentNotFound
     */
    public function getById(UuidInterface $id): CourierAggregate;

    /**
     * @throws ContentNotFound
     */
    public function getFreeCouriers(): array;
}