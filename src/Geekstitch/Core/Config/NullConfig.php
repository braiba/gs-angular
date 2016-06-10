<?php

namespace Geekstitch\Core\Config;

class NullConfig implements Config
{
    /**
     * @inheritDoc
     */
    public function get($name)
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getValue($name, $default = null)
    {
        return $default;
    }

    /**
     * @inheritDoc
     */
    public function asArray()
    {
        return array();
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        // Do nothing
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        // Do nothing
    }
}
