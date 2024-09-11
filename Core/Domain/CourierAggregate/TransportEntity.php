<?php

declare(strict_types=1);

namespace Core\Domain\CourierAggregate;

use InvalidArgumentException;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

final class TransportEntity
{
    private const SPEED_PEDESTRIAN = 1;
    private const SPEED_BICYCLE = 2;
    private const SPEED_CAR = 3;

    private function __construct(
        private UuidInterface $id,
        private string $name,
        private SpeedVO $speed,
    ) {
    }

    public static function pedestrian(): self
    {
        return new TransportEntity(
            id: (new UuidFactory())->fromString('0a442e8f-e7da-4c9c-a1da-9c95398ac5f7'),
            name: TransportEntityEnum::pedestrian->name,
            speed: new SpeedVO(self::SPEED_PEDESTRIAN),
        );
    }

    public static function bicycle(): self
    {
        return new TransportEntity(
            id: (new UuidFactory())->fromString('06a49935-0334-40b6-8b6f-ca7e3847bd73'),
            name: TransportEntityEnum::bicycle->name,
            speed: new SpeedVO(self::SPEED_BICYCLE),
        );
    }

    public static function car(): self
    {
        return new TransportEntity(
            id: (new UuidFactory())->fromString('d1db4036-9b2b-48ce-9e5f-27c47661fced'),
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
        return $this->id->equals($transport->id);
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
}