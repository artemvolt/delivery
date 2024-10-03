<?php
declare(strict_types=1);

namespace app\api\modules\api\v1\controllers;

use app\api\modules\api\components\BaseApiController;
use app\common\Api\Adapters\Http\Contract\Models\ErrorDto;
use app\common\Api\Adapters\Http\Contract\Models\LocationDto;
use app\common\Api\Adapters\Http\Contract\Models\OrderDto as ResponseOrderDto;
use app\common\Api\Adapters\Http\Contract\OrdersControllerContractInterface;
use app\common\Core\Application\UseCases\Commands\CreateOrder\CreateOrderCommandDto;
use app\common\Core\Application\UseCases\Commands\CreateOrder\CreateOrderCommandHandlerInterface;
use app\common\Core\Application\UseCases\Queries\GetUncompletedOrders\GetUncompletedOrdersQueryDto;
use app\common\Core\Application\UseCases\Queries\GetUncompletedOrders\GetUncompletedOrdersQueryHandlerInterface;
use DomainException;
use yii\rest\Controller;

final class OrdersController extends BaseApiController implements OrdersControllerContractInterface
{
    public function __construct(
        $id,
        $module,
        private readonly CreateOrderCommandHandlerInterface $createOrderCommandHandler,
        private readonly GetUncompletedOrdersQueryHandlerInterface $getUncompletedOrdersQueryHandler,
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
        $orders = $this->getUncompletedOrdersQueryHandler->handle(new GetUncompletedOrdersQueryDto())->orders;

        $resultOrders = [];
        foreach ($orders as $order) {
            $resultOrders[] = new ResponseOrderDto(
                id: $order->id,
                location: new LocationDto(
                    x: $order->location->x,
                    y: $order->location->y,
                )
            );
        }

        return $resultOrders;
    }
}