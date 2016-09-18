<?php

namespace tests\mocks;

final class ExampleFactory
{
    public function __invoke($class, ...$arguments)
    {
        return new $class(...$arguments);
    }
}
