<?php

declare(strict_types=1);

namespace app\api\modules\v1\controllers;

use app\common\Api\Adapters\Http\Contract\CouriersControllerContractInterface;
use app\common\Api\Adapters\Http\Contract\Models\CourierDto as ResponseCourierDto;
use app\common\Api\Adapters\Http\Contract\Models\LocationDto;
use app\common\Core\Application\UseCases\Queries\GetBusyCouriers\CourierDto;
use app\common\Core\Application\UseCases\Queries\GetBusyCouriers\GetBusyCouriersQueryDto;
use app\common\Core\Application\UseCases\Queries\GetBusyCouriers\GetBusyCouriersQueryHandler;
use yii\rest\Controller;
use OpenApi\Attributes as OA;

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
        return array_map(
            function (CourierDto $courierDto) {
                return new ResponseCourierDto(
                    id: $courierDto->id->toString(),
                    name: $courierDto->name,
                    location: new LocationDto(
                        x: $courierDto->location->x,
                        y: $courierDto->location->y,
                    )
                );
            },
            $this->getBusyCouriersQueryHandler->handle(new GetBusyCouriersQueryDto())->couriers
        );
    }
}