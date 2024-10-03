<?php
declare(strict_types=1);

namespace app\api\modules\v1\controllers;

use app\common\Api\Adapters\Http\Contract\Models\ErrorDto;
use app\common\Api\Adapters\Http\Contract\Models\LocationDto;
use app\common\Api\Adapters\Http\Contract\Models\OrderDto as ResponseOrderDto;
use app\common\Api\Adapters\Http\Contract\OrdersControllerContractInterface;
use app\common\Core\Application\UseCases\Commands\CreateOrder\CreateOrderCommandDto;
use app\common\Core\Application\UseCases\Commands\CreateOrder\CreateOrderCommandHandler;
use app\common\Core\Application\UseCases\Queries\GetUncompletedOrders\GetUncompletedOrdersQueryDto;
use app\common\Core\Application\UseCases\Queries\GetUncompletedOrders\GetUncompletedOrdersQueryHandler;
use app\common\Core\Application\UseCases\Queries\GetUncompletedOrders\OrderDto;
use DomainException;
use yii\rest\Controller;
use OpenApi\Attributes as OA;

final class OrdersController extends Controller implements OrdersControllerContractInterface
{
    public function __construct(
        $id,
        $module,
        private readonly CreateOrderCommandHandler $createOrderCommandHandler,
        private readonly GetUncompletedOrdersQueryHandler $getUncompletedOrdersQueryHandler,
        $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionCreate()
    {
        try {
            $this->createOrderCommandHandler->handle(
                new CreateOrderCommandDto(
                    basketId: rand(1, 1000),
                    street: "Customer street #" . rand(1, 1000),
                )
            );
            $this->response->statusCode = 201;
        } catch (DomainException $e) {
            return new ErrorDto(422, $e->getMessage());
        }
    }

    public function actionActive()
    {
        return array_map(
            function (OrderDto $orderDto) {
                return new ResponseOrderDto(
                    id: $orderDto->id,
                    location: new LocationDto(
                        x: $orderDto->location->x,
                        y: $orderDto->location->y,
                    )
                );
            },
            $this->getUncompletedOrdersQueryHandler->handle(new GetUncompletedOrdersQueryDto())->orders
        );
    }
}