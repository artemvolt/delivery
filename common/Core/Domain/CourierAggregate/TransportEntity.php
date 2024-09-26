<?php

declare(strict_types=1);

namespace app\common\Core\Domain\CourierAggregate;

use InvalidArgumentException;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

final class TransportEntity
{
    private const SPEED_PEDESTRIAN = 1;
    private const SPEED_BICYCLE = 2;
    private const SPEED_CAR = 3;

    private function __construct(
        private int $id,
        private string $name,
        private SpeedVO $speed,
    ) {
    }

    public static function pedestrian(): self
    {
        return new TransportEntity(
            id: 1,
            name: TransportEntityEnum::pedestrian->name,
            speed: new SpeedVO(self::SPEED_PEDESTRIAN),
        );
    }

    public static function bicycle(): self
    {
        return new TransportEntity(
            id: 2,
            name: TransportEntityEnum::bicycle->name,
            speed: new SpeedVO(self::SPEED_BICYCLE),
        );
    }

    public static function car(): self
    {
        return new TransportEntity(
            id: 3,
            name: TransportEntityEnum::car->name,
            speed: new SpeedVO(self::SPEED_CAR),
        );
    }

    public function getSpeed(): SpeedVO
    {
        return $this->speed;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromName(string $name): self
    {
        return match ($name) {
            TransportEntityEnum::pedestrian->name => self::pedestrian(),
            TransportEntityEnum::bicycle->name => self::bicycle(),
            TransportEntityEnum::car->name => self::car(),
            default => throw new InvalidArgumentException("Unknown name of transport: $name"),
        };
    }

    public function isEqual(TransportEntity $transport): bool
    {
        return $this->id === $transport->id;
    }

    /**
     * @return TransportEntity[]
     */
    public function getList(): array
    {
        return [
            self::pedestrian(),
            self::bicycle(),
            self::car(),
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}