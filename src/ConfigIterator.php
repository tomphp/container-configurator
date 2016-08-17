<?php

namespace TomPHP\ConfigServiceProvider;

use Iterator;
use RecursiveArrayIterator;

final class ConfigIterator implements Iterator
{
    /**
     * @var string[]
     */
    private $path = [];

    /**
     * @var RecursiveArrayIterator
     */
    private $stack = [];

    /**
     * @var RecursiveArrayIterator
     */
    private $current;

    /**
     * @var string
     */
    private $separator;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->separator = $config->getSeparator();
        $this->current   = new RecursiveArrayIterator($config->asArray());
    }

    public function current()
    {
        return $this->current->current();
    }

    public function key()
    {
        return implode($this->separator, array_merge($this->path, [$this->current->key()]));
    }

    public function next()
    {
        if ($this->current->hasChildren()) {
            $it = &$this->current;
            array_push($this->stack, $it);
            array_push($this->path, $it->key());
            $this->current = $it->getChildren();
        } else {
            $this->current->next();
        }
    }

    public function rewind()
    {
        if (!empty($this->stack)) {
            $this->current = array_shift($this->stack);
        }

        $this->stack = [];
        $this->path = [];

        $this->current->rewind();
    }

    public function valid()
    {
        if ($this->current->valid()) {
            return true;
        }

        if (empty($this->stack)) {
            return false;
        }

        array_pop($this->path);
        $this->current = array_pop($this->stack);
        $this->current->next();

        return $this->valid();
    }
}
