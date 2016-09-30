<?php

namespace tests\unit\TomPHP\ContainerConfigurator;

use PHPUnit_Framework_TestCase;
use tests\mocks\ExampleContainer;
use tests\mocks\ExampleContainerAdapter;
use tests\mocks\ExampleExtendedContainer;
use tests\mocks\NotContainerAdapter;
use TomPHP\ContainerConfigurator\ContainerAdapterFactory;
use TomPHP\ContainerConfigurator\Exception\NotContainerAdapterException;
use TomPHP\ContainerConfigurator\Exception\UnknownContainerException;

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
        assertInstanceOf(
            ExampleContainerAdapter::class,
            $this->subject->create(new ExampleContainer())
        );
    }

    public function testItCreatesAnInstanceOfTheConfiguratorForSubclassedContainer()
    {
        assertInstanceOf(
            ExampleContainerAdapter::class,
            $this->subject->create(new ExampleExtendedContainer())
        );
    }

    public function testItThrowsIfContainerIsNotKnown()
    {
        $this->expectException(UnknownContainerException::class);

        $this->subject->create(new \stdClass());
    }

    public function testItThrowsIfNotAContainerAdapter()
    {
        $this->subject = new ContainerAdapterFactory([
            ExampleContainer::class => NotContainerAdapter::class,
        ]);

        $this->expectException(NotContainerAdapterException::class);

        $this->subject->create(new ExampleContainer());
    }

    public function testItSetsTheContainerOnTheConfigurator()
    {
        $container    = new ExampleContainer();
        $configurator = $this->subject->create($container);

        assertSame($container, $configurator->getContainer());
    }
}
