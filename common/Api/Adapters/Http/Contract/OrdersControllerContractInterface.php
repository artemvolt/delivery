<?php

declare(strict_types=1);

namespace app\common\Api\Adapters\Http\Contract;

use OpenApi\Attributes as OA;

interface OrdersControllerContractInterface
{
    #[OA\POST(
        path: '/api/v1/orders',
        operationId: "CreateOrder",
        description: "Позволяет создать заказ с целью тестирования",
        summary: "Создать заказ",
        tags: ['Orders'],
        parameters: [],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Успешный ответ',
            ),
            new OA\Response(
                response: "default",
                description: "Ошибка",
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorDto'),
            ),
        ],
    )]
    public function actionCreate();

    #[OA\GET(
        path: '/api/v1/orders/active',
        operationId: "GetOrders",
        description: "Позволяет получить все незавершенные",
        summary: "Получить все незавершенные заказы",
        tags: ['Orders'],
        parameters: [],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/OrderDto'),
                )
            ),
            new OA\Response(
                response: "default",
                description: "Ошибка",
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorDto'),
            ),
        ],
    )]
    public function actionActive();
}