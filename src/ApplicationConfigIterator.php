<?php

namespace TomPHP\ContainerConfigurator;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * @internal
 */
final class ApplicationConfigIterator extends RecursiveIteratorIterator
{
    /**
     * @var string[]
     */
    private $path = [];

    /**
     * @var string
     */
    private $separator;

    /**
     * @param ApplicationConfig $config
     */
    public function __construct(ApplicationConfig $config)
    {
        parent::__construct(
            new RecursiveArrayIterator($config->asArray()),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $this->separator = $config->getSeparator();
    }

    public function key()
    {
        return implode($this->separator, array_merge($this->path, [parent::key()]));
    }

    public function next()
    {
        if ($this->callHasChildren()) {
            array_push($this->path, parent::key());
        }

        parent::next();
    }

    public function rewind()
    {
        $this->path = [];

        parent::rewind();
    }

    public function endChildren()
    {
        array_pop($this->path);
    }
}
