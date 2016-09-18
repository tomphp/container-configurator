<?php

namespace tests\unit\TomPHP\ContainerConfigurator\FileReader;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use tests\support\TestFileCreator;
use TomPHP\ContainerConfigurator\Exception\UnknownFileTypeException;
use TomPHP\ContainerConfigurator\FileReader\JSONFileReader;
use TomPHP\ContainerConfigurator\FileReader\PHPFileReader;
use TomPHP\ContainerConfigurator\FileReader\ReaderFactory;
use TomPHP\ContainerConfigurator\FileReader\YAMLFileReader;

final class ReaderFactoryTest extends PHPUnit_Framework_TestCase
{
    use TestFileCreator;

    /**
     * @var ReaderFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new ReaderFactory([
            '.php'  => PHPFileReader::class,
            '.json' => JSONFileReader::class,
            '.yaml' => YAMLFileReader::class,
            '.yml' => YAMLFileReader::class,
        ]);
    }

    /**
     * @dataProvider providerCreatesAppropriateFileReader
     *
     * @param string $extension
     * @param string $fileReaderClass
     */
    public function testCreatesAppropriateFileReader($extension, $fileReaderClass)
    {
        $filename = 'test' . $extension;

        $this->createTestFile($filename);

        $reader = $this->factory->create($this->getTestPath($filename));

        assertInstanceOf($fileReaderClass, $reader);
    }

    /**
     * @return \Generator
     */
    public function providerCreatesAppropriateFileReader()
    {
        $extensions = [
            '.json' => JSONFileReader::class,
            '.php' => PHPFileReader::class,
            '.yaml' => YAMLFileReader::class,
            '.yml' => YAMLFileReader::class,
        ];

        foreach ($extensions as $extension => $fileReaderClass) {
            yield [
                $extension,
                $fileReaderClass,
            ];
        }
    }

    public function testReturnsTheSameReaderForTheSameFileType()
    {
        $this->createTestFile('test1.php');
        $this->createTestFile('test2.php');

        $reader1 = $this->factory->create($this->getTestPath('test1.php'));
        $reader2 = $this->factory->create($this->getTestPath('test2.php'));

        assertSame($reader1, $reader2);
    }

    public function testItThrowsIfTheArgumentIsNotAFileName()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->factory->create('missing-file.xxx');
    }

    public function testItThrowsIfThereIsNoRegisteredReaderForGivenFileType()
    {
        $this->createTestFile('test.unknown');

        $this->expectException(UnknownFileTypeException::class);

        $this->factory->create($this->getTestPath('test.unknown'));
    }
}
