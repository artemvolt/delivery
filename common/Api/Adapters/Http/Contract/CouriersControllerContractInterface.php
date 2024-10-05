<?php

declare(strict_types=1);

namespace app\common\Api\Adapters\Http\Contract;

use OpenApi\Attributes as OA;

interface CouriersControllerContractInterface
{
    #[OA\GET(
        path: '/api/v1/couriers/all',
        operationId: "GetCouriers",
        description: "Позволяет получить всех курьеров",
        summary: "Получить всех курьеров",
        tags: ['Couriers'],
        parameters: [],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/CourierDto'),
                )
            ),
            new OA\Response(
                response: "default",
                description: "Ошибка",
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorDto'),
            ),
        ],
    )]
    public function actionAll();
}