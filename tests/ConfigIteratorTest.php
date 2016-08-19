<?php

namespace tests\TomPHP\ConfigServiceProvider;

use PHPUnit_Framework_TestCase;
use TomPHP\ConfigServiceProvider\Config;
use TomPHP\ConfigServiceProvider\ConfigIterator;

final class ConfigIteratorTest extends PHPUnit_Framework_TestCase
{
    public function testItIteratesOverSimpleConfigValues()
    {
        $iterator = new ConfigIterator(new Config([
            'keyA'   => 'valueA',
            'keyB'   => 'valueB',
        ]));

        $this->assertEquals(
            [
                'keyA'   => 'valueA',
                'keyB'   => 'valueB',
            ],
            iterator_to_array($iterator)
        );
    }

    public function testItIteratesRecursively()
    {
        $iterator = new ConfigIterator(new Config([
            'group1' => [
                'keyA'   => 'valueA',
            ],
            'group2' => [
                'keyB'   => 'valueB',
            ],
        ]));

        $this->assertEquals(
            [
                'group1' => [
                    'keyA' => 'valueA',
                ],
                'group1.keyA' => 'valueA',
                'group2' => [
                    'keyB' => 'valueB',
                ],
                'group2.keyB' => 'valueB',
            ],
            iterator_to_array($iterator)
        );
    }

    public function testItGoesMultipleLevels()
    {
        $iterator = new ConfigIterator(new Config([
            'group1' => [
                'keyA'   => 'valueA',
                'group2' => [
                    'keyB'   => 'valueB',
                ],
            ],
        ]));

        $this->assertEquals(
            [
                'group1' => [
                    'keyA' => 'valueA',
                    'group2' => [
                        'keyB'   => 'valueB',
                    ],
                ],
                'group1.keyA' => 'valueA',
                'group1.group2' => [
                    'keyB' => 'valueB',
                ],
                'group1.group2.keyB' => 'valueB',
            ],
            iterator_to_array($iterator)
        );
    }

    public function testItRewinds()
    {
        $iterator = new ConfigIterator(new Config([
            'group1' => [
                'keyA'   => 'valueA',
                'keyB'   => 'valueB',
                'keyC'   => 'valueC',
            ],
        ]));

        $iterator->next();
        $iterator->next();
        $iterator->next();

        $this->assertEquals(
            [
                'group1' => [
                    'keyA'   => 'valueA',
                    'keyB'   => 'valueB',
                    'keyC'   => 'valueC',
                ],
                'group1.keyA' => 'valueA',
                'group1.keyB' => 'valueB',
                'group1.keyC' => 'valueC',
            ],
            iterator_to_array($iterator)
        );
    }

    public function testItUsesADifferentSeparator()
    {
        $iterator = new ConfigIterator(new Config([
            'group1' => [
                'keyA'   => 'valueA',
            ],
        ], '->'));

        $this->assertEquals(
            [
                'group1' => [
                    'keyA' => 'valueA',
                ],
                'group1->keyA' => 'valueA',
            ],
            iterator_to_array($iterator)
        );
    }
}
