<?php

namespace tests\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\ConfiguratorFactory;
use tests\mocks\ExampleContainer;
use tests\mocks\ExampleExtendedContainer;

final class ConfiguratorFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ConfiguratorFactory
     */
    private $subject;

    protected function setUp()
    {
        $this->subject = new ConfiguratorFactory([
            'tests\mocks\ExampleContainer' => 'tests\mocks\ExampleConfigurator',
        ]);
    }

    public function testItCreatesAnInstanceOfTheConfigurator()
    {
        $this->assertInstanceOf(
            'tests\mocks\ExampleConfigurator',
            $this->subject->create(new ExampleContainer())
        );
    }

    public function testItCreatesAnInstanceOfTheConfiguratorForSubclassedContainer()
    {
        $this->assertInstanceOf(
            'tests\mocks\ExampleConfigurator',
            $this->subject->create(new ExampleExtendedContainer())
        );
    }

    public function testItThrowsIfContainerIsNotKnown()
    {
        $this->setExpectedException('TomPHP\ConfigServiceProvider\Exception\UnknownContainerException');

        $this->subject->create(new \stdClass());
    }

    public function testItSetsTheContainerOnTheConfigurator()
    {
        $container = new ExampleContainer();
        $configurator = $this->subject->create($container);

        $this->assertSame($container, $configurator->getContainer());
    }
}
