<?php

namespace Williamson\TPLinkSmartplug;

use Exception;
use IPTools\Range;
use InvalidArgumentException;
use Tightenco\Collect\Support\Collection;

class TPLinkManager
{
    protected $config;
    protected $devices;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Get a device object pre-configured in config
     *
     * @param string $name
     *
     * @return TPLinkDevice
     */
    public function device($name = 'default')
    {
        if (!isset($this->config[$name]) || !is_array($this->config[$name]) || empty($this->config[$name]['ip'])) {
            throw new InvalidArgumentException('You have not setup all the details for a device named ' . $name);
        }

        return new TPLinkDevice($this->config[$name], $name);
    }

    /**
     * Return current config
     *
     * @return array
     */
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
     * @param      $ipRange
     * @param int  $timeout
     * @param int  $timeoutStream
     * @param null $callbackFunction
     * @param int  $maxDiscoveredDevices
     *
     * @return Collection
     */
    public function autoDiscoverTPLinkDevices(
        $ipRange,
        $timeout = 1,
        $timeoutStream = 1,
        $callbackFunction = null,
        $maxDiscoveredDevices = 0
    ) {
        $discoveredCount = 0;

        return collect(Range::parse($ipRange))
            ->map(function ($ip) use (
                $timeout,
                $timeoutStream,
                $callbackFunction,
                $maxDiscoveredDevices,
                &$discoveredCount
            ) {
                // Discovered max devices, do not do checks for further devices
                if ($maxDiscoveredDevices > 0 && $discoveredCount >= $maxDiscoveredDevices) {
                    return null;
                }

                $response = $this->deviceResponse((string)$ip, $timeout, $timeoutStream);
                $device = $this->validTPLinkResponse($response, (string)$ip, $callbackFunction);

                if ($device) {
                    $discoveredCount++;

                    return $device;
                }

                return $response;
            })
            ->filter()
            ->values();
    }

    /**
     * Try sending systemInfo command to an ip.
     * Possible we may get a blank response, if querying another device which uses these ports
     *
     * @param $ip
     * @param $timeout
     * @param $timeoutStream
     *
     * @return null
     */
    protected function deviceResponse($ip, $timeout, $timeoutStream)
    {
        try {
            $device = new TPLinkDevice([
                'ip'             => $ip,
                'port'           => 9999,
                'timeout'        => $timeout,
                'timeout_stream' => $timeoutStream,
            ], 'autodiscovery');

            return $device->sendCommand(TPLinkCommand::systemInfo());
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Check the returned data JSON decodes
     * Make sure is not NULL, some devices may return a single character
     *
     * @param      $response
     * @param      $ip
     * @param null $callbackFunction
     *
     * @return mixed|TPLinkDevice
     */
    protected function validTPLinkResponse($response, $ip, $callbackFunction = null)
    {
        $jsonResponse = json_decode($response);

        return is_null($jsonResponse) ? $jsonResponse : $this->discoveredDevice($jsonResponse, $ip, $callbackFunction);
    }

    /**
     * Create a new discovered device instance and update config to contain new device
     *
     * @param      $jsonResponse
     * @param      $ip
     * @param null $callbackFunction
     *
     * @return TPLinkDevice
     */
    protected function discoveredDevice($jsonResponse, $ip, $callbackFunction = null)
    {
        $device = $this->newDevice(
            [
                'ip'         => (string)$ip,
                'port'       => 9999,
                'systemInfo' => $jsonResponse->system->get_sysinfo,
                'deviceType' => $this->detectDeviceType($jsonResponse),
            ],
            $this->detectDeviceName($jsonResponse));

        // Callback function during discovery
        if ($callbackFunction) {
            call_user_func($callbackFunction, $device);
        }

        return $device;
    }

    /**
     * Add a new device to the config setup
     *
     * @param $config
     * @param $name
     *
     * @return TPLinkDevice
     */
    public function newDevice($config, $name)
    {
        $this->config[$name] = $config;

        return new TPLinkDevice($config, $name);
    }

    private function detectDeviceType($jsonResponse)
    {
        return (isset($jsonResponse->system->get_sysinfo->type)) ? $jsonResponse->system->get_sysinfo->type : $jsonResponse->system->get_sysinfo->mic_type;
    }

    private function detectDeviceName($jsonResponse)
    {
        return $jsonResponse->system->get_sysinfo->alias;
    }
}