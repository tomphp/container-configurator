<?php

namespace tests\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Config;

final class ConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    private $config;

    protected function setUp()
    {
        $this->config = new Config([
            'keyA'   => 'valueA',
            'group1' => [
                'keyB' => 'valueB',
                'null' => null,
            ],
        ]);
    }

    public function testItProvidesAccessToSimpleScalarValues()
    {
        $this->assertEquals('valueA', $this->config['keyA']);
    }

    public function testItProvidesAccessToArrayValues()
    {
        $this->assertEquals(['keyB' => 'valueB', 'null' => null], $this->config['group1']);
    }

    public function testItProvidesToSubValuesUsingDotNotation()
    {
        $this->assertEquals('valueB', $this->config['group1.keyB']);
    }

    public function testItSaysIfAnEntryIsSet()
    {
        $this->assertTrue(isset($this->config['group1.keyB']));
    }

    public function testItSaysIfAnEntryIsNotSet()
    {
        $this->assertFalse(isset($this->config['bad.entry']));
    }

    public function testItSaysIfAnEntryIsSetIfItIsFalsey()
    {
        $this->assertTrue(isset($this->config['group1.null']));
    }

    public function testItCanBeConvertedToAnArray()
    {
        $this->assertEquals(
            [
                'keyA'   => 'valueA',
                'group1' => [
                    'keyB' => 'valueB',
                    'null' => null,
                ],
            ],
            $this->config->asArray()
        );
    }

    public function testItWorksWithADifferentSeperator()
    {
        $this->config = new Config([
            'group1' => [
                'keyA' => 'valueA',
            ],
        ], '->');
        $this->assertEquals('valueA', $this->config['group1->keyA']);
    }

    public function testItCannotHaveAValueSet()
    {
        $this->setExpectedException('TomPHP\ConfigServiceProvider\Exception\ReadOnlyException');

        $this->config['key'] = 'value';
    }

    public function testItCannotHaveAValueRemoved()
    {
        $this->setExpectedException('TomPHP\ConfigServiceProvider\Exception\ReadOnlyException');

        unset($this->config['keyA']);
    }
}
