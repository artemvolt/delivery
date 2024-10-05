<?php
declare(strict_types=1);

namespace app\common\Api\Adapters\Http\Contract\Models;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: "Ошибка",
    properties: [
        new OA\Property(
            property: 'code',
            description: "Код ошибки",
            type: 'integer',
            format: "int32",
            nullable: false,
        ),
        new OA\Property(
            property: 'message',
            description: "Текст ошибки",
            type: 'string',
            nullable: false,
        ),
    ],
)]
final class ErrorDto
{
    public function __construct(
        public readonly int $code,
        public readonly string $message,
    ) {
    }
}