<?php

declare(strict_types=1);

namespace integration\common\Infrastructure\Adapters\Postgres\Repositories;

use app\common\Core\Domain\CourierAggregate\CourierAggregate;
use app\common\Core\Domain\CourierAggregate\TransportEntity;
use app\common\Core\Domain\Model\SharedKernel\CoordinateVO;
use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use app\common\Core\Domain\OrderAggregate\OrderAggregate;
use app\common\Core\Domain\OrderAggregate\OrderStatusEntity;
use app\common\Infrastructure\Adapters\Postgres\Models\CourierModel;
use app\common\Infrastructure\Adapters\Postgres\Models\OrderModel;
use app\common\Infrastructure\Adapters\Postgres\Repositories\CourierRepository;
use app\common\Infrastructure\Adapters\Postgres\Repositories\OrderRepository;
use IntegrationTester;

/**
 * @TODO переделать тесты на запросы в бд,
 * Сперва создать состояние в бд и потом протестировать репозиторий
 */
class OrderRepositoryCest
{
    private ?OrderAggregate $order = null;
    private ?CourierAggregate $courier = null;

    public function _before(IntegrationTester $I)
    {
        OrderModel::deleteAll();
        CourierModel::deleteAll();

        $this->order = $this->createOrder(1, 1, 1);

        $this->courier = CourierAggregate::create(
            name: 'test',
            transport: TransportEntity::car(),
            location: new LocationVO(
                x: new CoordinateVO(1),
                y: new CoordinateVO(2),
            )
        );
    }

    public function testAddOrder(IntegrationTester $I)
    {
        $repository = new OrderRepository();
        $repository->addOrder($this->order);

        $I->assertCount(1, OrderModel::find()->all());
        $orderModel = OrderModel::findOne(['id' => $this->order->getId()]);
        $I->assertNotNull($orderModel);
        $I->assertEquals($this->order->getId(), $orderModel->id);
        $I->assertEquals($this->order->getStatus()->getId(), $orderModel->status_id);
        $I->assertEquals($this->order->getLocation()->getX()->getValue(), $orderModel->location_x);
        $I->assertEquals($this->order->getLocation()->getY()->getValue(), $orderModel->location_y);
        $I->assertEquals($this->order->getCourierId(), $orderModel->courier_id);
    }

    public function testUpdateAddOrder(IntegrationTester $I)
    {
        $courierRepository = new CourierRepository();
        $courierRepository->addCourier($this->courier);

        $repository = new OrderRepository();
        $repository->addOrder($this->order);

        $I->assertCount(1, OrderModel::find()->all());
        $orderModel = OrderModel::findOne(['id' => $this->order->getId()]);
        $I->assertNotNull($orderModel);
        $I->assertEquals($this->order->getId(), $orderModel->id);
        $I->assertEquals($this->order->getStatus()->getId(), $orderModel->status_id);
        $I->assertEquals($this->order->getLocation()->getX()->getValue(), $orderModel->location_x);
        $I->assertEquals($this->order->getLocation()->getY()->getValue(), $orderModel->location_y);
        $I->assertEquals($this->order->getCourierId(), $orderModel->courier_id);

        $this->order->assignCourier($this->courier);
        $repository->updateOrder($this->order);

        $I->assertCount(1, OrderModel::find()->all());
        $orderModel = OrderModel::findOne(['id' => $this->order->getId()]);
        $I->assertNotNull($orderModel);
        $I->assertEquals($this->order->getId(), $orderModel->id);
        $I->assertEquals($this->order->getStatus()->getId(), $orderModel->status_id);
        $I->assertEquals($this->order->getLocation()->getX()->getValue(), $orderModel->location_x);
        $I->assertEquals($this->order->getLocation()->getY()->getValue(), $orderModel->location_y);
        $I->assertNotNull($orderModel->courier_id);
        $I->assertEquals($this->order->getCourierId(), $orderModel->courier_id);
    }

    public function testGetById(IntegrationTester $I)
    {
        $repository = new OrderRepository();
        $repository->addOrder($this->order);

        $courierRepository = new CourierRepository();
        $courierRepository->addCourier($this->courier);

        $repository = new OrderRepository();
        $foundOrder = $repository->getById($this->order->getId());

        $I->assertEquals($foundOrder->getId(), $this->order->getId());
        $I->assertTrue($foundOrder->getStatus()->isEqual($this->order->getStatus()));
        $I->assertTrue($foundOrder->getLocation()->isEqual($this->order->getLocation()));
        $I->assertNull($foundOrder->getCourierId());

        $this->order->assignCourier($this->courier);
        $repository->updateOrder($this->order);

        $repository = new OrderRepository();
        $foundOrder = $repository->getById($this->order->getId());

        $I->assertEquals($foundOrder->getId(), $this->order->getId());
        $I->assertTrue($foundOrder->getStatus()->isEqual($this->order->getStatus()));
        $I->assertTrue($foundOrder->getLocation()->isEqual($this->order->getLocation()));
        $I->assertNotNull($foundOrder->getCourierId());
        $I->assertTrue($foundOrder->getCourierId()->equals($this->order->getCourierId()));
    }

    public function testGetCreatedOrders(IntegrationTester $I)
    {
        $repository = new OrderRepository();
        $repository->addOrder($this->order);

        $orderCreated = $this->createOrder(2, 1, 1);
        $repository->addOrder($orderCreated);

        $foundCreated = $repository->getCreatedOrders();
        $I->assertCount(2, $foundCreated);
        $I->assertTrue($foundCreated[0]->getStatus()->isEqual(OrderStatusEntity::created()));
        $I->assertTrue($foundCreated[1]->getStatus()->isEqual(OrderStatusEntity::created()));

        $courierRepository = new CourierRepository();
        $courierRepository->addCourier($this->courier);

        $orderCreated->assignCourier($this->courier);
        $repository->updateOrder($orderCreated);

        $foundCreated = $repository->getCreatedOrders();
        $I->assertCount(1, $foundCreated);
        $I->assertTrue($foundCreated[0]->getStatus()->isEqual(OrderStatusEntity::created()));
    }

    public function testGetAssignedOrders(IntegrationTester $I)
    {
        $repository = new OrderRepository();
        $repository->addOrder($this->order);

        $orderCreated = $this->createOrder(2, 1, 1);
        $repository->addOrder($orderCreated);

        $courierRepository = new CourierRepository();
        $courierRepository->addCourier($this->courier);

        $orderCreated->assignCourier($this->courier);
        $this->order->assignCourier($this->courier);
        $repository->updateOrder($this->order);
        $repository->updateOrder($orderCreated);

        $foundAssigned= $repository->getAssignedOrders();
        $I->assertCount(2, $foundAssigned);
        $I->assertTrue($foundAssigned[0]->getStatus()->isEqual(OrderStatusEntity::assigned()));
        $I->assertTrue($foundAssigned[1]->getStatus()->isEqual(OrderStatusEntity::assigned()));
    }

    private function createOrder(int $id, int $x, int $y)
    {
        return OrderAggregate::create(
            id: $id,
            location: new LocationVO(
                x: new CoordinateVO($x),
                y: new CoordinateVO($y),
            )
        );
    }
}
