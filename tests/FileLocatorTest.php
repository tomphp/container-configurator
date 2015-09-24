<?php

namespace tests\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\FileLocator;

final class FileLocatorTest extends PHPUnit_Framework_TestCase
{
    use TestFileCreator;

    /**
     * @var FileLocator
     */
    private $locator;

    protected function setUp()
    {
        $this->locator = new FileLocator();
    }

    public function testItFindsFilesByGlobbing()
    {
        $this->createTestFile('config1.php');
        $this->createTestFile('config2.php');
        $this->createTestFile('config.json');

        $files = $this->locator->locate([$this->getTestPath('*.php')]);

        $this->assertEquals([
            $this->getTestPath('config1.php'),
            $this->getTestPath('config2.php'),
        ], $files);
    }

    public function testItFindsFindsFilesByGlobbingWithBraces()
    {
        $this->createTestFile('global.php');
        $this->createTestFile('database.local.php');
        $this->createTestFile('nothing.php');
        $this->createTestFile('nothing.php.dist');

        $files = $this->locator->locate([$this->getTestPath('{,*.}{global,local}.php')]);

        $this->assertEquals([
            $this->getTestPath('global.php'),
            $this->getTestPath('database.local.php'),
        ], $files);
    }
}
