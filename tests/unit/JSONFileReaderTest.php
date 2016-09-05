<?php

namespace tests\unit\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use tests\support\TestFileCreator;
use TomPHP\ConfigServiceProvider\JSONFileReader;

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
        $this->assertInstanceOf('TomPHP\ConfigServiceProvider\FileReader', $this->reader);
    }

    public function testItThrowsIfFileDoesNotExist()
    {
        $this->setExpectedException('TomPHP\ConfigServiceProvider\Exception\FileNotFoundException');

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
        $this->setExpectedException('TomPHP\ConfigServiceProvider\Exception\InvalidConfigException');

        $this->createTestFile('config.json', 'not json');

        $this->reader->read($this->getTestPath('config.json'));
    }
}
