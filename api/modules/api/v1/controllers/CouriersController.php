<?php

declare(strict_types=1);

namespace app\api\modules\api\v1\controllers;

use app\api\modules\api\components\BaseApiController;
use app\common\Api\Adapters\Http\Contract\CouriersControllerContractInterface;
use app\common\Api\Adapters\Http\Contract\Models\CourierDto as ResponseCourierDto;
use app\common\Api\Adapters\Http\Contract\Models\LocationDto;
use app\common\Core\Application\UseCases\Queries\GetAllCouriers\GetAllCouriersQueryDto;
use app\common\Core\Application\UseCases\Queries\GetAllCouriers\GetAllCouriersQueryHandlerInterface;
use yii\filters\Cors;
use yii\rest\Controller;

final class CouriersController extends BaseApiController implements CouriersControllerContractInterface
{
    public function __construct(
        $id,
        $module,
        private readonly GetAllCouriersQueryHandlerInterface $getAllCouriersQueryHandler,
        $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionGetAll()
    {
        $couriers = $this->getAllCouriersQueryHandler->handle(new GetAllCouriersQueryDto())->couriers;

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