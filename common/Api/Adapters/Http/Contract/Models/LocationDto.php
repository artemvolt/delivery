<?php

declare(strict_types=1);

namespace app\common\Api\Adapters\Http\Contract\Models;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: "Локация",
    properties: [
        new OA\Property(
            property: 'x',
            description: "X",
            type: 'integer',
            nullable: false,
        ),
        new OA\Property(
            property: 'y',
            description: "Y",
            type: 'integer',
            nullable: false,
        ),
    ],
)]
final class LocationDto
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
    ) {
    }
}