<?php

declare(strict_types=1);

namespace app\common\Api\Adapters\Http\Contract\Models;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: "Заказ",
    properties: [
        new OA\Property(
            property: 'id',
            description: "Идентификатор",
            type: 'string',
            format: 'uuid',
            nullable: false,
        ),
        new OA\Property(
            property: 'location',
            ref: '#/components/schemas/LocationDto',
            description: "Геолокация",
        ),
    ],
)]
final class OrderDto
{
    public function __construct(
        public readonly string $id,
        public readonly LocationDto $location,
    ) {
    }
}