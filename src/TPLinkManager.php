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
            $device = new TPLinkDevice(['ip' => $ip, 'port' => 9999, 'timeout' => $timeout], 'autodiscovery');
            try{
                $systemInfo = json_decode($device->sendCommand(TPLinkCommand::systemInfo()));
                $this->newTPLinkDevice([
                    'ip' => (string)$ip,
                    'port' => 9999,
                    'systemInfo' => $systemInfo->system->get_sysinfo
                ], $systemInfo->system->get_sysinfo->alias);
            } catch(\UnexpectedValueException $e) {}
        }
    }
}