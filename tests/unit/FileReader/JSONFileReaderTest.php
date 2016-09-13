<?php

namespace tests\unit\TomPHP\ContainerConfigurator\FileReader;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;
use TomPHP\ContainerConfigurator\FileReader\FileReader;
use TomPHP\ContainerConfigurator\FileReader\JSONFileReader;
use tests\support\TestFileCreator;

final class JSONFileReaderTest extends PHPUnit_Framework_TestCase
{
    use TestFileCreator;

    /**
     * @var JSONFileReader
     */
    private $reader;

    protected function setUp()
    {
        $this->reader = new JSONFileReader();
    }

    public function testItIsAFileReader()
    {
        $this->assertInstanceOf(FileReader::class, $this->reader);
    }

    public function testItThrowsIfFileDoesNotExist()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->reader->read('file-which-does-not-exist');
    }

    public function testReadsAPHPConfigFile()
    {
        $config = ['key' => 'value', 'sub' => ['key' => 'value']];

        $this->createTestFile('config.json', json_encode($config));

        $this->assertEquals($config, $this->reader->read($this->getTestPath('config.json')));
    }

    public function testItThrowsIfTheConfigIsInvalid()
    {
        $this->expectException(InvalidConfigException::class);

        $this->createTestFile('config.json', 'not json');

        $this->reader->read($this->getTestPath('config.json'));
    }
}
