<?php
namespace Williamson\TPLinkSmartplug\tests;

use Williamson\TPLinkSmartplug\TPLinkDevice;
use Williamson\TPLinkSmartplug\TPLinkManager;

class TPLinkDeviceTest extends \PHPUnit_Framework_TestCase
{

    protected $config;

    public function setUp()
    {
        $this->config = [
            'ip'   => '192.168.1.100',
            'port' => '9999',
        ];
    }

    /** @test */
    public function it_just_is_a_placeholder()
    {
        $this->assertTrue(true);
    }


}