<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Postgres\Repositories;

use app\common\Core\Domain\CourierAggregate\CourierAggregate;
use app\common\Core\Domain\CourierAggregate\CourierStatusEntity;
use app\common\Core\Domain\CourierAggregate\CourierStatusEnum;
use app\common\Core\Domain\CourierAggregate\TransportEntity;
use app\common\Core\Domain\Model\SharedKernel\CoordinateVO;
use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use app\common\Core\Ports\CourierRepositoryInterface;
use app\common\Infrastructure\Adapters\Postgres\Models\CourierModel;
use DomainException;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

final class CourierRepository implements CourierRepositoryInterface
{
    public function addCourier(CourierAggregate $courier): void
    {
        $courierModel = new CourierModel();
        $courierModelLoaded = $this->mapToModel($courier, $courierModel);
        if (!$courierModelLoaded->save()) {
            throw new DomainException(
                "Could not save Courier {$courier->getId()}. Errors: " . implode(", ", $courierModel->getFirstErrors())
            );
        }
    }

    public function updateCourier(CourierAggregate $courier): void
    {
        $courierModel = CourierModel::findOne(['id' => $courier->getId()->toString()]);
        if (null === $courierModel) {
            throw new DomainException("Courier {$courier->getId()} not found");
        }

        $courierModelLoaded = $this->mapToModel($courier, $courierModel);
        if (!$courierModelLoaded->save()) {
            throw new DomainException(
                "Could not update Courier {$courier->getId()}. Errors: " . implode(", ", $courierModel->getFirstErrors())
            );
        }
    }

    public function getById(UuidInterface $id): ?CourierAggregate
    {
        $found = CourierModel::findOne(['id' => $id->toString()]);
        if (null === $found) {
            return null;
        }
        return $this->mapToEntity($found);
    }

    /**
     * @return CourierAggregate[]
     */
    public function getFreeCouriers(): array
    {
        return array_map(
            fn (CourierModel $courierModel) => $this->mapToEntity($courierModel),
            CourierModel::findAll(['status_id' => CourierStatusEnum::free->value])
        );
    }

    private function mapToModel(CourierAggregate $courier, CourierModel $courierModel): CourierModel
    {
        $courierModel->id = $courier->getId()->toString();
        $courierModel->name = $courier->getName();
        $courierModel->status_id = $courier->getStatus()->getId();
        $courierModel->transport_id = $courier->getTransport()->getId();
        $courierModel->location_x = $courier->getLocation()->getX()->getValue();
        $courierModel->location_y = $courier->getLocation()->getY()->getValue();
        return $courierModel;
    }

    private function mapToEntity(CourierModel $courierModel): CourierAggregate
    {
        $uuidFactory = new UuidFactory();
        return CourierAggregate::createExisting(
            uuid: $uuidFactory->fromString($courierModel->id),
            name: $courierModel->name,
            transport: TransportEntity::fromName($courierModel->transport->name),
            location: new LocationVO(
                x: new CoordinateVO($courierModel->location_x),
                y: new CoordinateVO($courierModel->location_y),
            ),
            statusEntity: CourierStatusEntity::fromId($courierModel->status_id),
        );
    }
}