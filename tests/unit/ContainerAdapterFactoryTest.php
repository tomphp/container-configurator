<?php

namespace tests\unit\TomPHP\ContainerConfigurator;

use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\ContainerAdapterFactory;
use TomPHP\ContainerConfigurator\Exception\UnknownContainerException;
use tests\mocks\ExampleContainer;
use tests\mocks\ExampleContainerAdapter;
use tests\mocks\ExampleExtendedContainer;

final class ContainerAdapterFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerAdapterFactory
     */
    private $subject;

    protected function setUp()
    {
        $this->subject = new ContainerAdapterFactory([
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
        $this->expectException(UnknownContainerException::class);

        $this->subject->create(new \stdClass());
    }

    public function testItSetsTheContainerOnTheConfigurator()
    {
        $container = new ExampleContainer();
        $configurator = $this->subject->create($container);

        $this->assertSame($container, $configurator->getContainer());
    }
}
