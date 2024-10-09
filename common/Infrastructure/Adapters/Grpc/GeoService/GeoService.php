<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Grpc\GeoService;

use app\common\Core\Domain\Model\SharedKernel\CoordinateVO;
use app\common\Core\Domain\Model\SharedKernel\LocationVO;
use app\common\Infrastructure\Exceptions\InfrastructureException;
use Geo\GeoClient;
use Geo\GetGeolocationReply;
use Geo\GetGeolocationRequest;
use RuntimeException;

final class GeoService implements GeoServiceInterface
{
    public function __construct(
        private readonly string $host,
        private readonly array $options = [],
    ) {
    }

    public function getLocationByStreetName(string $streetName): LocationVO
    {
        $client = new GeoClient($this->host, $this->options);
        $request = new GetGeolocationRequest();
        $request->setStreet($streetName);
        /**
         * @var GetGeolocationReply $response
         * @var object $status
         */
        list($response, $status) = $client->GetGeolocation($request)->wait();
        if (null === $response) {
            throw new InfrastructureException($status->details);
        }

        $locationResponse = $response->getLocation();

        return new LocationVO(
            x: new CoordinateVO($locationResponse->getX()),
            y: new CoordinateVO($locationResponse->getY())
        );
    }
}