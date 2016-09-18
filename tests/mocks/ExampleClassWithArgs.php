<?php

namespace tests\mocks;

final class ExampleClassWithArgs
{
    private $constructorArgs = [];

    public function __construct(...$constructorArgs)
    {
        $this->constructorArgs = $constructorArgs;
    }

    public function getConstructorArgs()
    {
        return $this->constructorArgs;
    }
}
