<?php

declare(strict_types=1);

namespace app\api\modules\v1\controllers;

use app\common\Api\Adapters\Http\Contract\CouriersControllerContractInterface;
use app\common\Api\Adapters\Http\Contract\Models\CourierDto as ResponseCourierDto;
use app\common\Api\Adapters\Http\Contract\Models\LocationDto;
use app\common\Core\Application\UseCases\Queries\GetBusyCouriers\GetBusyCouriersQueryDto;
use app\common\Core\Application\UseCases\Queries\GetBusyCouriers\GetBusyCouriersQueryHandler;
use yii\rest\Controller;

final class CouriersController extends Controller implements CouriersControllerContractInterface
{
    public function __construct(
        $id,
        $module,
        private readonly GetBusyCouriersQueryHandler $getBusyCouriersQueryHandler,
        $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionBusy()
    {
        $couriers = $this->getBusyCouriersQueryHandler->handle(new GetBusyCouriersQueryDto())->couriers;

        $resultCouriers = [];
        foreach ($couriers as $courier) {
            $resultCouriers[] = new ResponseCourierDto(
                id: $courier->id->toString(),
                name: $courier->name,
                location: new LocationDto(
                    x: $courier->location->x,
                    y: $courier->location->y,
                )
            );
        }

        return $resultCouriers;
    }
}