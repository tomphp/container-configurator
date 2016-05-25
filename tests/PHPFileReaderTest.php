<?php

namespace tests\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\PHPFileReader;

final class PHPFileReaderTest extends PHPUnit_Framework_TestCase
{
    use TestFileCreator;

    /**
     * @var PHPFileReader
     */
    private $reader;

    protected function setUp()
    {
        $this->reader = new PHPFileReader();
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
        $config = ['key' => 'value'];
        $code = '<?php return ' . var_export($config, true) . ';';

        $this->createTestFile('config.php', $code);

        $this->assertEquals($config, $this->reader->read($this->getTestPath('config.php')));
    }

    public function testItThrowsIfTheConfigIsInvalid()
    {
        $this->setExpectedException('TomPHP\ConfigServiceProvider\Exception\InvalidConfigException');

        $code = '<?php return 123;';
        $this->createTestFile('config.php', $code);

        $this->reader->read($this->getTestPath('config.php'));
    }
}
