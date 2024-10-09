<?php

declare(strict_types=1);

namespace app\common\Core\Domain\CourierAggregate;

use app\common\Core\Domain\Aggregates\AggregateInterface;
use app\common\Core\Domain\Events\DomainEventInterface;
use app\common\Core\Domain\Model\SharedKernel\CoordinateVO;
use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use DomainException;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

class CourierAggregate implements AggregateInterface
{
    /**
     * @var DomainEventInterface[] $domainEvents
     */
    private array $domainEvents = [];

    private function __construct(
        private UuidInterface $id,
        private string $name,
        private TransportEntity $transport,
        private LocationVO $location,
        private CourierStatusEntity $status,
    ) {
    }

    public static function create(
        string $name,
        TransportEntity $transport,
        LocationVO $location,
    ): self {
        $uuidFactory = new UuidFactory();
        return new self(
            id: $uuidFactory->uuid7(),
            name: $name,
            transport: $transport,
            location: $location,
            status:  CourierStatusEntity::free(),
        );
    }

    public static function createExisting(
        UuidInterface $uuid,
        string $name,
        TransportEntity $transport,
        LocationVO $location,
        CourierStatusEntity $statusEntity,
    ): self {
        return new self(
            id: $uuid,
            name: $name,
            transport: $transport,
            location: $location,
            status: $statusEntity,
        );
    }

    /**
     * @throws DomainException
     */
    public function setBusyStatus(): void
    {
        $busy = CourierStatusEntity::busy();
        if ($this->status->isEqual($busy)) {
            throw new DomainException("Courier has already busy status");
        }

        $this->status = $busy;
    }

    /**
     * @throws DomainException
     */
    public function setFreeStatus(): void
    {
        $free = CourierStatusEntity::free();
        if ($this->status->isEqual($free)) {
            throw new DomainException("Courier has already free status");
        }

        $this->status = $free;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getStatus(): CourierStatusEntity
    {
        return $this->status;
    }

    public function calculateTimeToPoint(LocationVO $location): float
    {
        $xPoints = abs($this->location->getX()->getValue() - $location->getX()->getValue());
        $yPoints = abs($this->location->getY()->getValue() - $location->getY()->getValue());
        $totalPoints = $xPoints + $yPoints;
        return round($totalPoints / $this->transport->getSpeed()->getValue(), 1);
    }

    public function move(LocationVO $location): void
    {
        $countStepsPerOnce = $this->transport->getSpeed()->getValue();
        $currentX = $this->location->getX()->getValue();
        $currentY = $this->location->getY()->getValue();
        $toX = $location->getX()->getValue();
        $toY = $location->getY()->getValue();

        if ($this->location->isEqual($location)) {
            return;
        }

        $remainedSteps = $countStepsPerOnce;
        $nextX = $currentX;
        if ($currentX !== $toX) {
            $nextX = $this->getNextX($location, $remainedSteps);
            $remainedSteps = $remainedSteps - abs($currentX - $toX);

            if ($remainedSteps <= 0) {
                $this->location = new LocationVO(
                    x: new CoordinateVO($nextX),
                    y: new CoordinateVO($currentY),
                );
                return;
            }
        }

        if ($currentY !== $toY) {
            $nextY = $this->getNextY($location, $remainedSteps);
            $this->location = new LocationVO(
                x: new CoordinateVO($nextX),
                y: new CoordinateVO($nextY),
            );
        }

    }

    public function getLocation(): LocationVO
    {
        return $this->location;
    }

    private function getNextX(LocationVO $location, int $remainedSteps): int
    {
        $currentX = $this->location->getX()->getValue();
        $toX = $location->getX()->getValue();
        $directionXRight = $currentX < $toX;
        $nextX = $directionXRight ? $currentX + $remainedSteps : $currentX - $remainedSteps;

        if ($directionXRight) {
            return min($nextX, $toX);
        }

        return max($nextX, $toX);
    }

    private function getNextY(LocationVO $location, int $remainedSteps): int
    {
        $currentY = $this->location->getY()->getValue();
        $toY = $location->getY()->getValue();
        $directionYBottom = $currentY < $toY;
        $nextY = $directionYBottom ? $currentY + $remainedSteps : $currentY - $remainedSteps;
        if ($directionYBottom) {
            return min($nextY, $toY);
        }

        return max($nextY, $toY);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTransport(): TransportEntity
    {
        return $this->transport;
    }

    public function getEvents(): array
    {
        return $this->domainEvents;
    }

    public function clearDomainEvents(): void
    {
        $this->domainEvents = [];
    }
}
