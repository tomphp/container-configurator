<?php

namespace tests\unit\TomPHP\ConfigServiceProvider\FileReader;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Exception\UnknownFileTypeException;
use TomPHP\ConfigServiceProvider\FileReader\JSONFileReader;
use TomPHP\ConfigServiceProvider\FileReader\PHPFileReader;
use TomPHP\ConfigServiceProvider\FileReader\ReaderFactory;

final class ReaderFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ReaderFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new ReaderFactory([
            '.php'  => PHPFileReader::class,
            '.json' => JSONFileReader::class,
        ]);
    }

    public function testCreatesAReader()
    {
        $reader = $this->factory->create('test.php');

        $this->assertInstanceOf(PHPFileReader::class, $reader);
    }

    public function testCreatesAnotherReader()
    {
        $reader = $this->factory->create('test.json');

        $this->assertInstanceOf(JSONFileReader::class, $reader);
    }

    public function testReturnsTheSameReaderForTheSameFileType()
    {
        $reader1 = $this->factory->create('test1.php');
        $reader2 = $this->factory->create('test2.php');

        $this->assertSame($reader1, $reader2);
    }

    public function testItThrowsIfThereIsNoRegisteredReaderForGivenFileType()
    {
        $this->setExpectedException(UnknownFileTypeException::class);

        $this->factory->create('test.unknown');
    }
}
