<?php

namespace tests\acceptance;

use TomPHP\ConfigServiceProvider\ConfigureContainer;

trait SupportsApplicationConfig
{
    public function testItAddsConfigToTheContainer()
    {
        $config = ['keyA' => 'valueA'];

        ConfigureContainer::fromArray($this->container, $config);

        $this->assertEquals('valueA', $this->container->get('config.keyA'));
    }

    public function testItAddsGroupedConfigToTheContainer()
    {
        $config = ['group1' => ['keyA' => 'valueA']];

        ConfigureContainer::fromArray($this->container, $config);

        $this->assertEquals(['keyA' => 'valueA'], $this->container->get('config.group1'));
        $this->assertEquals('valueA', $this->container->get('config.group1.keyA'));
    }

    public function testItAddsConfigToTheContainerWithAnAlternativeSeparator()
    {
        $config = ['keyA' => 'valueA'];

        ConfigureContainer::fromArray($this->container, $config, ['config_separator' => '/']);

        $this->assertEquals('valueA', $this->container->get('config/keyA'));
    }

    public function testItAddsConfigToTheContainerWithAnAlterantivePrefix()
    {
        $config = ['keyA' => 'valueA'];

        ConfigureContainer::fromArray($this->container, $config, ['config_prefix' => 'settings']);

        $this->assertEquals('valueA', $this->container->get('settings.keyA'));
    }

    public function testItAddsConfigToTheContainerWithNoPrefix()
    {
        $config = ['keyA' => 'valueA'];

        ConfigureContainer::fromArray($this->container, $config, ['config_prefix' => '']);

        $this->assertEquals('valueA', $this->container->get('keyA'));
    }
}
