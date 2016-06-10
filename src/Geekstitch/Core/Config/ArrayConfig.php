<?php

namespace Geekstitch\Core\Config;

class ArrayConfig implements Config
{
    protected $iteratorIndex = 0;

    protected $keys;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
        $this->keys = array_keys($data);
    }

    /**
     * @inheritDoc
     */
    public function get($name)
    {
        if (!isset($this->data[$name])) {
            return new NullConfig();
        }

        $value = $this->data[$name];
        if (!is_array($value)) {
            return new NullConfig();
        }

        return new ArrayConfig($value);
    }

    /**
     * @inheritDoc
     */
    public function getValue($name, $default = null)
    {
        if (!isset($this->data[$name])) {
            return $default;
        }

        return $this->data[$name];
    }

    /**
     * @inheritDoc
     */
    public function asArray()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        $key = $this->key();
        if ($key === null || !array_key_exists($this->data, $key)) {
            return null;
        }

        $value = $this->data[$key];
        if (!is_array($value)) {
            return null;
        }

        return new ArrayConfig($value);
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->iteratorIndex++;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        if (!array_key_exists($this->iteratorIndex, $this->keys)) {
            return null;
        }

        return $this->keys[$this->iteratorIndex];
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return array_key_exists($this->iteratorIndex, $this->keys);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->iteratorIndex = 0;
    }
}
