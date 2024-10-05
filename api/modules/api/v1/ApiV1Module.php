<?php

declare(strict_types=1);

namespace app\api\modules\api\v1;

use OpenApi\Attributes as OA;
use yii\base\Module;

#[OA\Info(
    version: "1.0.0",
    description: "Позволяет получить всех курьеров",
    title: "Delivery",
)]
#[OA\Server(
    url: "http://api-local-delivery.biz",
    description: "Позволяет получить всех курьеров",
)]
class ApiV1Module extends Module
{
}