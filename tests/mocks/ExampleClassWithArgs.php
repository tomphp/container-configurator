<?php

namespace tests\mocks;

final class ExampleClassWithArgs
{
    private $constructorArgs = [];

    public function __construct()
    {
        $this->constructorArgs = func_get_args();
    }

    public function getConstructorArgs()
    {
        return $this->constructorArgs;
    }
}
