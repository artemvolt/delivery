<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Grpc\GeoService;

use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use app\common\Infrastructure\Exceptions\InfrastructureExceptionInterface;

interface GeoServiceInterface
{
    /**
     * @throws InfrastructureExceptionInterface
     */
    public function getLocationByStreetName(string $streetName): LocationVO;
}