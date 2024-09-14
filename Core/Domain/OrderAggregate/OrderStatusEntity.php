<?php
declare(strict_types=1);

namespace Core\Domain\OrderAggregate;

use InvalidArgumentException;
use ValueError;

final class OrderStatusEntity
{
    private function __construct(
        private int $id,
        private string $name,
    ) {
    }

    public static function created(): self
    {
        return new OrderStatusEntity(
            id: OrderStatusEnum::created->value,
            name: OrderStatusEnum::created->name,
        );
    }

    public static function assigned(): self
    {
        return new OrderStatusEntity(
            id: OrderStatusEnum::assigned->value,
            name: OrderStatusEnum::assigned->name,
        );
    }

    public static function completed(): self
    {
        return new OrderStatusEntity(
            id: OrderStatusEnum::completed->value,
            name: OrderStatusEnum::completed->name,
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromId(int $id): self
    {
        try {
            $status = OrderStatusEnum::from($id);
        } catch (ValueError $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        return new OrderStatusEntity(
            id: $status->value,
            name: $status->name,
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromName(string $name): self
    {
        $status = match ($name) {
            OrderStatusEnum::created->name => OrderStatusEnum::created,
            OrderStatusEnum::completed->name => OrderStatusEnum::completed,
            OrderStatusEnum::assigned->name => OrderStatusEnum::assigned,
            default => throw new InvalidArgumentException("$name doesn't contain in available names.")
        };

        return self::fromId($status->value);
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return OrderStatusEntity[]
     */
    public function getList(): array
    {
        return [
            self::created(),
            self::assigned(),
            self::completed()
        ];
    }
}