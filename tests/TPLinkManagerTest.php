<?php
namespace Williamson\TPLinkSmartplug\tests;

use Williamson\TPLinkSmartplug\TPLinkDevice;
use Williamson\TPLinkSmartplug\TPLinkManager;

class TPLinkManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $config;

    public function setUp()
    {
        $this->config = [
            'heater'     => [
                'ip'   => '192.168.1.100',
                'port' => '9999',
            ],
            'incomplete' => [

            ],
            'incorrect'  => '192.168.1.101:9999',
        ];
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_should_throw_an_exception_if_the_requested_device_name_does_not_have_config_data()
    {
        $tp = new TPLinkManager($this->config);

        $device = $tp->device('unknownDevice');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_should_throw_an_exception_if_the_requested_device_name_has_incomplete_config_data()
    {
        $tp = new TPLinkManager($this->config);

        $device = $tp->device('incomplete');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_should_throw_an_exception_if_the_requested_device_name_has_incorrect_config_data()
    {
        $tp = new TPLinkManager($this->config);

        $device = $tp->device('incorrect');
    }

    /** @test */
    public function it_should_return_a_tp_device_if_config_data_is_found_for_it()
    {
        $tp = new TPLinkManager($this->config);

        $device = $tp->device('heater');

        $this->assertInstanceOf(TPLinkDevice::class, $device);
    }
}