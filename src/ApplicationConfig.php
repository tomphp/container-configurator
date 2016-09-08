<?php

namespace TomPHP\ConfigServiceProvider;

use ArrayAccess;
use IteratorAggregate;
use TomPHP\ConfigServiceProvider\Exception\EntryDoesNotExistException;
use TomPHP\ConfigServiceProvider\Exception\ReadOnlyException;
use Assert\Assertion;

final class ApplicationConfig implements ArrayAccess, IteratorAggregate
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $separator;

    /**
     * @param array  $config
     * @param string $separator
     */
    public function __construct(array $config, $separator = '.')
    {
        Assertion::string($separator);

        $this->config    = $config;
        $this->separator = $separator;
    }

    public function merge(array $config)
    {
        $this->config = array_replace_recursive($this->config, $config);
    }

    /**
     * @param string $separator
     *
     * @return void
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

    public function getIterator()
    {
        return new ApplicationConfigIterator($this);
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_keys(iterator_to_array(new ApplicationConfigIterator($this)));
    }

    public function offsetExists($offset)
    {
        try {
            $this->traverseConfig($this->getPath($offset));
        } catch (EntryDoesNotExistException $e) {
            return false;
        }

        return true;
    }

    public function offsetGet($offset)
    {
        return $this->traverseConfig($this->getPath($offset));
    }

    public function offsetSet($offset, $value)
    {
        throw ReadOnlyException::fromClassName(__CLASS__);
    }

    public function offsetUnset($offset)
    {
        throw ReadOnlyException::fromClassName(__CLASS__);
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return $this->config;
    }

    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    private function getPath($offset)
    {
        return explode($this->separator, $offset);
    }

    private function traverseConfig(array $path)
    {
        $pointer = &$this->config;

        foreach ($path as $node) {
            if (!is_array($pointer) || !array_key_exists($node, $pointer)) {
                throw EntryDoesNotExistException::fromKey(implode($this->separator, $path));
            }

            $pointer = &$pointer[$node];
        }

        return $pointer;
    }
}
