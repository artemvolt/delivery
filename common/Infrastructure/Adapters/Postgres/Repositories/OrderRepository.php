<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Postgres\Repositories;

use app\common\Core\Domain\Model\SharedKernel\CoordinateVO;
use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use app\common\Core\Domain\OrderAggregate\OrderAggregate;
use app\common\Core\Domain\OrderAggregate\OrderStatusEntity;
use app\common\Core\Domain\OrderAggregate\OrderStatusEnum;
use app\common\Core\Ports\OrderRepositoryInterface;
use app\common\Infrastructure\Adapters\Postgres\Models\OrderModel;
use DomainException;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

final class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private readonly UuidFactory $uuidFactory,
    ) {
    }

    public function addOrder(OrderAggregate $order): void
    {
        $orderModel = new OrderModel();
        $orderModel = $this->mapToModel($order, $orderModel);
        if (!$orderModel->save()) {
            throw new DomainException("Order {$order->getId()} could not save. Errors: "
                . implode(", ", $orderModel->getFirstErrors())
            );
        }
    }

    public function updateOrder(OrderAggregate $order): void
    {
        $orderModel = OrderModel::findOne(['id' => $order->getId()]);
        if (null === $orderModel) {
            throw new DomainException("Order {$order->getId()} not found");
        }

        $orderModel = $this->mapToModel($order, $orderModel);
        if (!$orderModel->save()) {
            throw new DomainException("Order {$order->getId()} could not save. Errors: " .
                implode(", ", $orderModel->getFirstErrors())
            );
        }
    }

    public function getById(UuidInterface $orderId): ?OrderAggregate
    {
        $orderModel = OrderModel::findOne(['id' => $orderId]);
        if (null === $orderModel) {
            return null;
        }
        return $this->mapToEntity($orderModel);
    }

    public function getCreatedOrders(): array
    {
        return array_map(
            fn (OrderModel $orderModel) => $this->mapToEntity($orderModel),
            OrderModel::find()->andWhere(['status_id' => OrderStatusEnum::created->value])->all(),
        );
    }

    public function getAssignedOrders(): array
    {
        return array_map(
            fn (OrderModel $orderModel) => $this->mapToEntity($orderModel),
            OrderModel::find()->andWhere(['status_id' => OrderStatusEnum::assigned->value])->all(),
        );
    }

    /**
     * @throws DomainException
     */
    private function mapToModel(OrderAggregate $order, OrderModel $orderModel): OrderModel
    {
        $orderModel->id = $order->getId()->toString();
        $orderModel->location_x = $order->getLocation()->getX()->getValue();
        $orderModel->location_y = $order->getLocation()->getY()->getValue();
        $orderModel->status_id = $order->getStatus()->getId();
        $orderModel->courier_id = $order->getCourierId()?->toString();
        return $orderModel;
    }

    private function mapToEntity(OrderModel $orderModel): OrderAggregate
    {
        $uuidFactory = new UuidFactory();

        return OrderAggregate::createExisting(
            id: $this->uuidFactory->fromString($orderModel->id),
            location: new LocationVO(
                x: new CoordinateVO($orderModel->location_x),
                y: new CoordinateVO($orderModel->location_y),
            ),
            status: OrderStatusEntity::fromId($orderModel->status_id),
            courierId: $orderModel->courier_id ? $uuidFactory->fromString($orderModel->courier_id) : null,
        );
    }
}