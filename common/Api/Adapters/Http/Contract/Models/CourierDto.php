<?php

declare(strict_types=1);

namespace app\common\Api\Adapters\Http\Contract\Models;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: "Курьер",
    properties: [
        new OA\Property(
            property: 'id',
            description: "Идентификатор",
            type: 'string',
            format: 'uuid',
            nullable: false,
        ),
        new OA\Property(
            property: 'name',
            description: "Имя",
            type: 'string',
            nullable: false,
        ),
        new OA\Property(
            property: 'location',
            ref: '#/components/schemas/LocationDto',
            description: "Геолокация",
        ),
    ],
)]
final class CourierDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly LocationDto $location,
    ) {
    }
}