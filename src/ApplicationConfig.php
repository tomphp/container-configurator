<?php

namespace TomPHP\ContainerConfigurator;

use ArrayAccess;
use IteratorAggregate;
use TomPHP\ContainerConfigurator\Exception\EntryDoesNotExistException;
use TomPHP\ContainerConfigurator\Exception\ReadOnlyException;

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
        \Assert\that($separator)->string()->notEmpty();

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
        \Assert\that($separator)->string()->notEmpty();

        $this->separator = $separator;
    }

    public function getIterator()
    {
        return new ApplicationConfigIterator($this);
    }

    /**
     * @return array<int|string>
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

    /**
     * @throws EntryDoesNotExistException
     */
    public function offsetGet($offset)
    {
        return $this->traverseConfig($this->getPath($offset));
    }

    /**
     * @throws ReadOnlyException
     */
    public function offsetSet($offset, $value)
    {
        throw ReadOnlyException::fromClassName(__CLASS__);
    }

    /**
     * @throws ReadOnlyException
     */
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

    /**
     * @return mixed
     *
     * @throws EntryDoesNotExistException
     */
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
