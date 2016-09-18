<?php

namespace tests\unit\TomPHP\ContainerConfigurator;

use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\InflectorDefinition;

final class InflectorDefinitionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var InflectorDefinition
     */
    private $subject;

    protected function setUp()
    {
        $this->subject = new InflectorDefinition(
            'interface_name',
            ['method1' => ['arg1', 'arg2']]
        );
    }

    public function testGetInterfaceReturnsTheInterfaceName()
    {
        assertEquals('interface_name', $this->subject->getInterface());
    }

    public function testGetMethodsReturnsTheMethods()
    {
        assertEquals(
            ['method1' => ['arg1', 'arg2']],
            $this->subject->getMethods()
        );
    }
}
