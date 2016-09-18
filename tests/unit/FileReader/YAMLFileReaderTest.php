<?php

namespace tests\unit\TomPHP\ContainerConfigurator\FileReader;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Yaml;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;
use TomPHP\ContainerConfigurator\FileReader\FileReader;
use TomPHP\ContainerConfigurator\FileReader\YAMLFileReader;

final class YAMLFileReaderTest extends PHPUnit_Framework_TestCase
{
    use TestFileCreator;

    /**
     * @var YAMLFileReader
     */
    private $reader;

    protected function setUp()
    {
        $this->reader = new YAMLFileReader();
    }

    public function testItIsAFileReader()
    {
        assertInstanceOf(FileReader::class, $this->reader);
    }

    public function testItThrowsIfFileDoesNotExist()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->reader->read('file-which-does-not-exist');
    }

    public function testReadsAYAMLConfigFile()
    {
        $config = ['key' => 'value', 'sub' => ['key' => 'value']];

        $this->createTestFile('config.yml', Yaml\Yaml::dump($config));

        assertEquals($config, $this->reader->read($this->getTestPath('config.yml')));
    }

    public function testItThrowsIfTheConfigIsInvalid()
    {
        $this->expectException(InvalidConfigException::class);

        $this->createTestFile('config.yml', '[not yaml;');

        $this->reader->read($this->getTestPath('config.yml'));
    }
}
