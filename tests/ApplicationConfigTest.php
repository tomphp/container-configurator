<?php

namespace tests\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\ApplicationConfig;

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

    public function testItReturnsAllItsKeys()
    {
        $this->assertEquals(
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
        $this->config = new ApplicationConfig([
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

    /**
     * @group from_files_factory
     */
    public function testItCreatesFromParsingFiles()
    {
        $configData = [
            'keyA' => 'valueA',
            'keyB' => 'valueB',
        ];

        $this->createPHPConfigFile('config.php', $configData);

        $config = ApplicationConfig::fromFiles([
            $this->getTestPath('*'),
        ]);

        $this->assertEquals($configData, $config->asArray());
    }

    /**
     * @group from_files_factory
     */
    public function testItCanOverrideFromFilesDefaults()
    {
        $configData = [
            'group' => [
                'key' => 'value',
            ],
        ];

        $this->createPHPConfigFile('config.php', $configData);

        $config = ApplicationConfig::fromFiles([ $this->getTestPath('*') ], '/');

        $this->assertEquals(
            [
                'group'     => ['key' => 'value'],
            ],
            $config->asArray()
        );
    }

    /**
     * @group from_files_factory
     */
    public function testItMergesConfigsFromFiles()
    {
        $config1 = ['a' => 1, 'b' => 5];
        $config2 = ['b' => 2, 'c' => 7];
        $config3 = ['c' => 3, 'd' => 4];

        $this->createPHPConfigFile('config1.php', $config1);
        $this->createPHPConfigFile('config2.php', $config2);
        $this->createJSONConfigFile('config3.json', $config3);

        $config = ApplicationConfig::fromFiles(
            [ $this->getTestPath('*') ]
        );

        $this->assertEquals(
            ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4],
            $config->asArray()
        );
    }

    /**
     * @group from_files_factory
     */
    public function testItThrowsWhenCreatingFromFilesAndNoConfigFilesAreFound()
    {
        $this->setExpectedException(
            'TomPHP\ConfigServiceProvider\Exception\NoMatchingFilesException'
        );

        ApplicationConfig::fromFiles([$this->getTestPath('*')]);
    }
}
