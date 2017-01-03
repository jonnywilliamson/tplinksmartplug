<?php
namespace Williamson\TPLinkSmartplug;

use InvalidArgumentException;

class TPLinkManager
{
    protected $config;
    protected $devices;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function device($name = 'default')
    {
        if (!isset($this->config[$name]) || !is_array($this->config[$name]) || empty($this->config[$name]['ip'])) {
            throw new InvalidArgumentException('You have not setup the details for a device named ' . $name);
        }

        return $this->newTPLinkDevice($this->config[$name], $name);
    }

    protected function newTPLinkDevice($config, $name)
    {
        return new TPLinkDevice($config, $name);
    }
}