<?php

namespace Williamson\TPLinkSmartplug\tests;

use ReflectionClass;
use ReflectionMethod;
use Williamson\TPLinkSmartplug\TPLinkCommand;

class TPLinkCommandTest extends \PHPUnit_Framework_TestCase
{


    /** @test checks all commands returned are an array */
    public function it_checks_all_commands_returned_are_an_array()
    {
        $reflector = new ReflectionClass(TPLinkCommand::class);
        $methods = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            if ($method->getNumberOfParameters() == 0) {
                $name = $method->getName();
                $this->assertInternalType('array', TPLinkCommand::$name());
            }
        }
    }

    //TODO Methods that require a parameter

}

