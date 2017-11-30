<?php

namespace Williamson\TPLinkSmartplug;

use InvalidArgumentException;
use IPTools\Range;

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

    public function autoDiscoverTPLinkDevices($ipRange, $timeout = 1)
    {
        $ips = Range::parse($ipRange);
        foreach($ips AS $ip){
            // Try to connect to a IP and port
            $device = new TPLinkDevice(['ip' => $ip, 'port' => 9999, 'timeout' => $timeout], 'autodiscovery');
            try{
                // Try sending systemInfo command
                // Possible we may get a blank response, if querying another device which uses these ports
                $response = $device->sendCommand(TPLinkCommand::systemInfo());
                if(!empty($response)){
                    // Check the returned data JSON decodes
                    // Make sure is not NULL, some devices may return a single character
                    // LB100 Series seems to respond on port 9999, however return a bad string
                    // TODO:: investigate LB100 support
                    $jsonResponse = json_decode($response);
                    if(!is_null($jsonResponse)){
                        $this->newTPLinkDevice([
                            'ip' => (string)$ip,
                            'port' => 9999,
                            'systemInfo' => $jsonResponse->system->get_sysinfo
                        ], $jsonResponse->system->get_sysinfo->alias);
                    }
                }
            } catch(\UnexpectedValueException $e) {}
        }
    }
}