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
        $names = array_map(
            fn (OrderStatusEnum $orderStatusEnum) => $orderStatusEnum->name,
            OrderStatusEnum::cases(),
        );

        if (!in_array($name, $names)) {
            throw new InvalidArgumentException("$name doesn't contain in available names.");
        }

        $namesKeys = array_combine(
            array_map(
                fn (OrderStatusEnum $orderStatusEnum) => $orderStatusEnum->name,
                OrderStatusEnum::cases(),
            ),
            array_map(
                fn (OrderStatusEnum $orderStatusEnum) => $orderStatusEnum->value,
                OrderStatusEnum::cases(),
            )
        );
        $statusValue = $namesKeys[$name];
        return self::fromId($statusValue);
    }

    public function getId(): int
    {
        return $this->id;
    }
}