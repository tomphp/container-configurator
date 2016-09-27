<?php

namespace tests\unit\TomPHP\ContainerConfigurator;

use PHPUnit_Framework_TestCase;
use TomPHP\ContainerConfigurator\ApplicationConfig;

final class ApplicationConfigIteratorTest extends PHPUnit_Framework_TestCase
{
    public function testItIteratesOverSimpleConfigValues()
    {
        $iterator = new ApplicationConfig([
            'keyA'   => 'valueA',
            'keyB'   => 'valueB',
        ]);

        assertEquals(
            [
                'keyA'   => 'valueA',
                'keyB'   => 'valueB',
            ],
            iterator_to_array($iterator)
        );
    }

    public function testItIteratesRecursively()
    {
        $iterator = new ApplicationConfig([
            'group1' => [
                'keyA'   => 'valueA',
            ],
            'group2' => [
                'keyB'   => 'valueB',
            ],
        ]);

        assertEquals(
            [
                'group1' => [
                    'keyA' => 'valueA',
                ],
                'group1.keyA' => 'valueA',
                'group2'      => [
                    'keyB' => 'valueB',
                ],
                'group2.keyB' => 'valueB',
            ],
            iterator_to_array($iterator)
        );
    }

    public function testItGoesMultipleLevels()
    {
        $iterator = new ApplicationConfig([
            'group1' => [
                'keyA'   => 'valueA',
                'group2' => [
                    'keyB'   => 'valueB',
                ],
            ],
        ]);

        assertEquals(
            [
                'group1' => [
                    'keyA'   => 'valueA',
                    'group2' => [
                        'keyB'   => 'valueB',
                    ],
                ],
                'group1.keyA'   => 'valueA',
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
        $iterator = new ApplicationConfig([
            'group1' => [
                'keyA'   => 'valueA',
                'keyB'   => 'valueB',
                'keyC'   => 'valueC',
            ],
        ]);

        next($iterator);
        next($iterator);
        next($iterator);

        assertEquals(
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
        $iterator = new ApplicationConfig([
            'group1' => [
                'keyA'   => 'valueA',
            ],
        ], '->');

        assertEquals(
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
