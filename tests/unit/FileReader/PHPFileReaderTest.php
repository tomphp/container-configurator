<?php

namespace tests\unit\TomPHP\ContainerConfigurator\FileReader;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\Exception\InvalidConfigException;
use TomPHP\ContainerConfigurator\FileReader\FileReader;
use TomPHP\ContainerConfigurator\FileReader\PHPFileReader;
use tests\support\TestFileCreator;

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
        $this->assertInstanceOf(FileReader::class, $this->reader);
    }

    public function testItThrowsIfFileDoesNotExist()
    {
        $this->expectException(InvalidArgumentException::class);

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
        $this->expectException(InvalidConfigException::class);

        $code = '<?php return 123;';
        $this->createTestFile('config.php', $code);

        $this->reader->read($this->getTestPath('config.php'));
    }
}
