<?php

namespace Williamson\TPLinkSmartplug;

use InvalidArgumentException;
use IPTools\Range;
use Tightenco\Collect\Support\Collection;

class TPLinkManager
{
    protected $config;
    protected $devices;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function device($name = 'default')
    {
        if (!isset($this->config[$name]) || !is_array($this->config[$name]) || empty($this->config[$name]['ip'])) {
            throw new InvalidArgumentException('You have not setup the details for a device named ' . $name);
        }

        return new TPLinkDevice($this->config[$name], $name);
    }

    protected function newTPLinkDevice($config, $name)
    {
        $this->config[$name] = $config;
        return new TPLinkDevice($config, $name);
    }

    public function deviceList()
    {
        return $this->config;
    }

    /**
     * Will return a collection of all TPLink devices auto discovered
     * on the IP Range given.
     *
     * These will already have been added to the global config during
     * discovery.
     *
     * @param     $ipRange
     * @param int $timeout
     *
     * @return Collection
     */
    public function autoDiscoverTPLinkDevices($ipRange, $timeout = 1)
    {
        return collect(Range::parse($ipRange))
            ->map(function ($ip) use ($timeout) {
                $response = $this->deviceResponse($ip, $timeout);

                return is_null($response) ? $response : $this->validTPLinkResponse($response, $ip);
            })
            ->filter();
    }

    /**
     * Try sending systemInfo command to an ip.
     * Possible we may get a blank response, if querying another device which uses these ports
     *
     * @param $ip
     * @param $timeout
     *
     * @return null
     */
    protected function deviceResponse($ip, $timeout)
    {
        try {
            $device = new TPLinkDevice(['ip' => $ip, 'port' => 9999, 'timeout' => $timeout], 'autodiscovery');

            return $device->sendCommand(TPLinkCommand::systemInfo());
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Check the returned data JSON decodes
     * Make sure is not NULL, some devices may return a single character
     * LB100 Series seems to respond on port 9999, however return a bad string
     * TODO:: investigate LB100 support
     *
     * @param $response
     * @param $ip
     *
     * @return mixed|TPLinkDevice
     */
    protected function validTPLinkResponse($response, $ip)
    {
        $jsonResponse = json_decode($response);

        return is_null($jsonResponse) ? $jsonResponse : $this->discoveredDevice($jsonResponse, $ip);
    }

    /**
     * Create a new discovered device instance and update config to contain new device
     *
     * @param $jsonResponse
     * @param $ip
     *
     * @return TPLinkDevice
     */
    protected function discoveredDevice($jsonResponse, $ip)
    {
        return $this->newTPLinkDevice([
            'ip'         => (string)$ip,
            'port'       => 9999,
            'systemInfo' => $jsonResponse->system->get_sysinfo,
        ], $jsonResponse->system->get_sysinfo->alias);
    }

}
