<?php

namespace tests\unit\TomPHP\ContainerConfigurator;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\ApplicationConfig;
use TomPHP\ContainerConfigurator\Exception\ReadOnlyException;

final class ApplicationConfigTest extends PHPUnit_Framework_TestCase
{
    use TestFileCreator;

    /**
     * @var ApplicationConfig
     */
    private $config;

    protected function setUp()
    {
        $this->config = new ApplicationConfig([
            'keyA'   => 'valueA',
            'group1' => [
                'keyB' => 'valueB',
                'null' => null,
            ],
        ]);
    }

    public function testItProvidesAccessToSimpleScalarValues()
    {
        assertEquals('valueA', $this->config['keyA']);
    }

    public function testItProvidesAccessToArrayValues()
    {
        assertEquals(['keyB' => 'valueB', 'null' => null], $this->config['group1']);
    }

    public function testItProvidesToSubValuesUsingDotNotation()
    {
        assertEquals('valueB', $this->config['group1.keyB']);
    }

    public function testItSaysIfAnEntryIsSet()
    {
        assertTrue(isset($this->config['group1.keyB']));
    }

    public function testItSaysIfAnEntryIsNotSet()
    {
        assertFalse(isset($this->config['bad.entry']));
    }

    public function testItSaysIfAnEntryIsSetIfItIsFalsey()
    {
        assertTrue(isset($this->config['group1.null']));
    }

    public function testItReturnsAllItsKeys()
    {
        assertEquals(
            [
                'keyA',
                'group1',
                'group1.keyB',
                'group1.null',
            ],
            $this->config->getKeys()
        );
    }

    public function testItCanBeConvertedToAnArray()
    {
        assertEquals(
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
        $this->config = new ApplicationConfig([
            'group1' => [
                'keyA' => 'valueA',
            ],
        ], '->');
        assertEquals('valueA', $this->config['group1->keyA']);
    }

    public function testItThrowsForAnEmptySeparatorOnConstruction()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->config = new ApplicationConfig([], '');
    }

    public function testItCannotHaveAValueSet()
    {
        $this->expectException(ReadOnlyException::class);

        $this->config['key'] = 'value';
    }

    public function testItCannotHaveAValueRemoved()
    {
        $this->expectException(ReadOnlyException::class);

        unset($this->config['keyA']);
    }

    public function testItMergesInNewConfig()
    {
        $config = new ApplicationConfig([
            'group' => [
                'keyA' => 'valueA',
                'keyB' => 'valueX',
            ],
        ]);

        $config->merge(['group' => ['keyB' => 'valueB']]);

        assertSame('valueA', $config['group.keyA']);
        assertSame('valueB', $config['group.keyB']);
    }

    public function testItUpdatesTheSeparator()
    {
        $config = new ApplicationConfig([
            'group' => [
                'keyA' => 'valueA',
            ],
        ]);

        $config->setSeparator('/');

        assertSame('valueA', $config['group/keyA']);
    }

    public function testItThrowsForAnEmptySeparatorWhenSettingSeparator()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->config = new ApplicationConfig([]);
        $this->config->setSeparator('');
    }
}
