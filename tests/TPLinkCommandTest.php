<?php

namespace Williamson\TPLinkSmartplug\tests;

use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;
use Williamson\TPLinkSmartplug\TPLinkCommand;

class TPLinkCommandTest extends \PHPUnit_Framework_TestCase
{


    /** @test checks all commands returned are an array */
    public function it_checks_all_commands_returned_are_an_array()
    {
        $reflector = new ReflectionClass(TPLinkCommand::class);
        $methods = collect($reflector->getMethods(ReflectionMethod::IS_PUBLIC));

        $methods
            ->filter(function (ReflectionMethod $method) {
                return $method->getNumberOfParameters() === 0;
            })
            ->each(function (ReflectionMethod $method) {
                $name = $method->getName();
                $this->assertInternalType('array', TPLinkCommand::$name());
            });
    }

}

