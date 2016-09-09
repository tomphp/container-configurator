<?php

namespace tests\unit\TomPHP\ConfigServiceProvider\FileReader;

use PHPUnit_Framework_TestCase;
use tests\support\TestFileCreator;
use TomPHP\ConfigServiceProvider\Exception\FileNotFoundException;
use TomPHP\ConfigServiceProvider\Exception\InvalidConfigException;
use TomPHP\ConfigServiceProvider\FileReader\FileReader;
use TomPHP\ConfigServiceProvider\FileReader\JSONFileReader;

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
        $this->expectException(FileNotFoundException::class);

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
