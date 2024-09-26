<?php


namespace Integration\common\Infrastructure\Adapters\Postgres\Repositories;

use app\common\Core\Domain\CourierAggregate\CourierAggregate;
use app\common\Core\Domain\CourierAggregate\CourierStatusEntity;
use app\common\Core\Domain\CourierAggregate\CourierStatusEnum;
use app\common\Core\Domain\CourierAggregate\TransportEntity;
use app\common\Core\Domain\Model\SharedKernel\CoordinateVO;
use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use app\common\Infrastructure\Adapters\Postgres\Models\CourierModel;
use app\common\Infrastructure\Adapters\Postgres\Repositories\CourierRepository;
use \IntegrationTester;

class CourierRepositoryCest
{
    private ?CourierAggregate $courier = null;

    public function _before(IntegrationTester $I)
    {
        CourierModel::deleteAll();
        $this->courier = CourierAggregate::create(
            name: 'test',
            transport: TransportEntity::car(),
            location: new LocationVO(
                x: new CoordinateVO(1),
                y: new CoordinateVO(2),
            )
        );
    }

    public function testAddCourier(IntegrationTester $I)
    {
        $repository = new CourierRepository();
        $repository->addCourier($this->courier);

        $I->assertCount(1, CourierModel::find()->all());
        $courierModel = CourierModel::findOne(['id' => $this->courier->getId()->toString()]);
        $I->assertNotNull($courierModel);
        $I->assertEquals($this->courier->getName(), $courierModel->name);
        $I->assertEquals($this->courier->getTransport()->getId(), $courierModel->transport_id);
        $I->assertEquals($this->courier->getStatus()->getId(), $courierModel->status_id);
        $I->assertEquals($this->courier->getLocation()->getX()->getValue(), $courierModel->location_x);
        $I->assertEquals($this->courier->getLocation()->getY()->getValue(), $courierModel->location_y);
    }

    public function testUpdateCourier(IntegrationTester $I)
    {
        $repository = new CourierRepository();
        $repository->addCourier($this->courier);

        $this->courier->setBusyStatus();
        $repository->updateCourier($this->courier);

        $courierModel = CourierModel::findOne(['id' => $this->courier->getId()->toString()]);
        $I->assertNotNull($courierModel);
        $I->assertEquals(CourierStatusEnum::busy->value, $courierModel->status_id);

        $this->courier->setFreeStatus();
        $repository->updateCourier($this->courier);

        $courierModel = CourierModel::findOne(['id' => $this->courier->getId()->toString()]);
        $I->assertNotNull($courierModel);
        $I->assertEquals(CourierStatusEnum::free->value, $courierModel->status_id);
    }

    public function testGetById(IntegrationTester $I)
    {
        $repository = new CourierRepository();
        $repository->addCourier($this->courier);

        $repository = new CourierRepository();
        $courier = $repository->getById($this->courier->getId());
        $I->assertEquals($courier->getId()->toString(), $this->courier->getId()->toString());
        $I->assertEquals($courier->getName(), $this->courier->getName());
        $I->assertTrue($courier->getStatus()->isEqual($this->courier->getStatus()));
        $I->assertTrue($courier->getLocation()->isEqual($this->courier->getLocation()));
        $I->assertTrue($courier->getTransport()->isEqual($this->courier->getTransport()));
        $I->assertTrue($courier->getStatus()->isEqual($this->courier->getStatus()));
    }

    public function testGetFreeCouriers(IntegrationTester $I)
    {
        $repository = new CourierRepository();
        $repository->addCourier($this->courier);

        $courierTwo =  CourierAggregate::create(
            name: 'test2',
            transport: TransportEntity::bicycle(),
            location: new LocationVO(
                x: new CoordinateVO(2),
                y: new CoordinateVO(3),
            )
        );
        $courierTwo->setBusyStatus();

        $repository->addCourier($courierTwo);

        $freeCouriers = $repository->getFreeCouriers();
        $I->assertCount(1, $freeCouriers);
        $freeCourier = $freeCouriers[0];
        $I->assertTrue($this->courier->getId()->equals($freeCourier->getId()));
        $I->assertTrue(CourierStatusEntity::free()->isEqual($this->courier->getStatus()));
    }
}
