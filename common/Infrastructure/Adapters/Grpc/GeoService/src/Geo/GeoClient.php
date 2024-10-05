<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Geo;

/**
 * The Geo service definition.
 */
class GeoClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * Get Geolocation
     * @param \Geo\GetGeolocationRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function GetGeolocation(\Geo\GetGeolocationRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/geo.Geo/GetGeolocation',
        $argument,
        ['\Geo\GetGeolocationReply', 'decode'],
        $metadata, $options);
    }

}
