<?php

namespace tests\acceptance;

use Pimple\Container;

final class PimpleContainerWrapper extends Container
{
    public function get($id)
    {
        return $this[$id];
    }

    public function has($id)
    {
        return isset($this[$id]);
    }
}
