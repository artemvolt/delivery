<?php

declare(strict_types=1);

namespace app\common\Core\Ports;

use app\common\Core\Domain\CourierAggregate\CourierAggregate;
use Codeception\Exception\ContentNotFound;
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