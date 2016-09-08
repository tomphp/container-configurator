<?php

namespace tests\unit\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use tests\mocks\ExampleContainerAdapter;
use TomPHP\ConfigServiceProvider\ConfiguratorFactory;
use tests\mocks\ExampleContainer;
use tests\mocks\ExampleExtendedContainer;
use TomPHP\ConfigServiceProvider\Exception\UnknownContainerException;

final class ConfiguratorFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ConfiguratorFactory
     */
    private $subject;

    protected function setUp()
    {
        $this->subject = new ConfiguratorFactory([
            ExampleContainer::class => ExampleContainerAdapter::class,
        ]);
    }

    public function testItCreatesAnInstanceOfTheContainerAdapter()
    {
        $this->assertInstanceOf(
            ExampleContainerAdapter::class,
            $this->subject->create(new ExampleContainer())
        );
    }

    public function testItCreatesAnInstanceOfTheConfiguratorForSubclassedContainer()
    {
        $this->assertInstanceOf(
            ExampleContainerAdapter::class,
            $this->subject->create(new ExampleExtendedContainer())
        );
    }

    public function testItThrowsIfContainerIsNotKnown()
    {
        $this->setExpectedException(UnknownContainerException::class);

        $this->subject->create(new \stdClass());
    }

    public function testItSetsTheContainerOnTheConfigurator()
    {
        $container = new ExampleContainer();
        $configurator = $this->subject->create($container);

        $this->assertSame($container, $configurator->getContainer());
    }
}
