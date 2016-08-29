<?php

namespace tests\TomPHP\ConfigServiceProvider\League;

use PHPUnit_Framework_TestCase;
use League\Container\ServiceProvider\ServiceProviderInterface;
use TomPHP\ConfigServiceProvider\League\AggregateServiceProvider;
use League\Container\ContainerInterface;
use Prophecy\Argument;

final class AggregateServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceProviderInterface
     */
    private $provider1;

    /**
     * @var ServiceProviderInterface
     */
    private $provider2;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var AggregateServiceProvider
     */
    private $subject;

    protected function setUp()
    {
        $this->provider1 = $this->prophesize('tests\mocks\BootableServiceProvider');
        $this->provider2 = $this->prophesize('League\Container\ServiceProvider\ServiceProviderInterface');
        $this->container = $this->prophesize('League\Container\ContainerInterface');

        $this->provider1->provides()->willReturn(['service1', 'service2']);
        $this->provider2->provides()->willReturn(['service3', 'service4']);
        $this->provider1->setContainer(Argument::any())->willReturn();
        $this->provider2->setContainer(Argument::any())->willReturn();

        $this->subject = new AggregateServiceProvider([
            $this->provider1->reveal(),
            $this->provider2->reveal(),
        ]);

        $this->subject->setContainer($this->container->reveal());
    }

    public function testItIsAServiceProvider()
    {
        $this->assertInstanceOf(
            'League\Container\ServiceProvider\ServiceProviderInterface',
            $this->subject
        );
    }

    public function testItIsABootableServiceProvider()
    {
        $this->assertInstanceOf(
            'League\Container\ServiceProvider\BootableServiceProviderInterface',
            $this->subject
        );
    }

    public function testItSetsTheContainerOfEachServiceProvider()
    {
        $this->provider1->setContainer($this->container)->shouldHaveBeenCalled();
        $this->provider2->setContainer($this->container)->shouldHaveBeenCalled();
    }

    public function testGetContainerReturnsTheContainer()
    {
        $this->assertSame(
            $this->container->reveal(),
            $this->subject->getContainer()
        );
    }

    public function testItMergesTheProvidesOfTheSubContainers()
    {
        $this->assertEquals(
            ['service1', 'service2', 'service3', 'service4'],
            $this->subject->provides()
        );
    }

    public function testItCallsRegisterOnAllSubProviders()
    {
        $this->provider1->register()->shouldBeCalled();
        $this->provider2->register()->shouldBeCalled();

        $this->subject->register();
    }

    public function testItBootsBootableServiceProviders()
    {
        $this->provider1->boot()->shouldBeCalled();

        $this->subject->boot();
    }
}
