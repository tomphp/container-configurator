<?php

namespace tests\unit\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\ReaderFactory;

final class ReaderFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ReaderFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new ReaderFactory([
            '.php' => 'TomPHP\ConfigServiceProvider\PHPFileReader',
            '.json' => 'TomPHP\ConfigServiceProvider\JSONFileReader',
        ]);
    }

    public function testCreatesAReader()
    {
        $reader = $this->factory->create('test.php');

        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\PHPFileReader',
            $reader
        );
    }

    public function testCreatesAnotherReader()
    {
        $reader = $this->factory->create('test.json');

        $this->assertInstanceOf(
            'TomPHP\ConfigServiceProvider\JSONFileReader',
            $reader
        );
    }

    public function testReturnsTheSameReaderForTheSameFileType()
    {
        $reader1 = $this->factory->create('test1.php');
        $reader2 = $this->factory->create('test2.php');

        $this->assertSame($reader1, $reader2);
    }

    public function testItThrowsIfThereIsNoRegisteredReaderForGivenFileType()
    {
        $this->setExpectedException('TomPHP\ConfigServiceProvider\Exception\UnknownFileTypeException');

        $this->factory->create('test.unknown');
    }
}
