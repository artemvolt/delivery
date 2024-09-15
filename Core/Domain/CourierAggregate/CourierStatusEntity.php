<?php

declare(strict_types=1);

namespace Core\Domain\CourierAggregate;

use InvalidArgumentException;
use ValueError;

final class CourierStatusEntity
{
    private function __construct(
        private int $id,
        private string $name,
    ) {
    }

    public static function free(): self
    {
        return new CourierStatusEntity(
            id: CourierStatusEnum::free->value,
            name: CourierStatusEnum::free->name,
        );
    }

    public static function busy(): self
    {
        return new CourierStatusEntity(
            id: CourierStatusEnum::busy->value,
            name: CourierStatusEnum::busy->name,
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromId(int $id): self
    {
        try {
            $status = CourierStatusEnum::from($id);
        } catch (ValueError $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        return new CourierStatusEntity(
            id: $status->value,
            name: $status->name,
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return CourierStatusEntity[]
     */
    public function getList(): array
    {
        return [
            self::free(),
            self::busy(),
        ];
    }

    public function isEqual(CourierStatusEntity $status): bool
    {
        return $this->getId() === $status->getId();
    }
}