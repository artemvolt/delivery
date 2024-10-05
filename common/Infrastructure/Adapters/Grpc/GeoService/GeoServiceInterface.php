<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Grpc\GeoService;

use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use RuntimeException;

interface GeoServiceInterface
{
    /**
     * @throws RuntimeException
     */
    public function getLocationByStreetName(string $streetName): LocationVO;
}