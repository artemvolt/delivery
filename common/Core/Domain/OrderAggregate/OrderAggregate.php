<?php

declare(strict_types=1);

namespace app\common\Core\Domain\OrderAggregate;

use app\common\Core\Domain\CourierAggregate\CourierAggregate;
use app\common\Core\Domain\CourierAggregate\CourierStatusEntity;
use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use DomainException;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

final class OrderAggregate
{
    private function __construct(
        private UuidInterface $id,
        private LocationVO $location,
        private OrderStatusEntity $status,
        private ?UuidInterface $courierId,
    ) {
    }

    public static function create(
        UuidInterface $id,
        LocationVO $location,
    ): self
    {
        return new self(
            id: $id,
            location: $location,
            status: OrderStatusEntity::created(),
            courierId: null,
        );
    }

    public static function createExisting(
        UuidInterface $id,
        LocationVO $location,
        OrderStatusEntity $status,
        ?UuidInterface $courierId,
    ): self
    {
        return new self(
            id: $id,
            location: $location,
            status: $status,
            courierId: $courierId,
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getLocation(): LocationVO
    {
        return $this->location;
    }

    public function getStatus(): OrderStatusEntity
    {
        return $this->status;
    }

    public function getCourierId(): ?UuidInterface
    {
        return $this->courierId;
    }

    /**
     * @throws DomainException
     */
    public function assignCourier(CourierAggregate $courier): void
    {
        if ($courier->getStatus()->isEqual(CourierStatusEntity::busy())) {
            throw new DomainException("Courier #{$courier->getId()} is busy. You can't assign him");
        }

        $this->courierId = $courier->getId();
        $this->status = OrderStatusEntity::assigned();
    }

    /**
     * @throws DomainException
     */
    public function toCompleted(): void
    {
        if (!$this->getStatus()->isEqual(OrderStatusEntity::assigned())) {
            throw new DomainException("Status must be Assigned");
        }

        $this->status = OrderStatusEntity::completed();
    }
}