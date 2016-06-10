<?php

namespace Geekstitch\Core\Config;

use Iterator;

interface Config extends Iterator
{
    /**
     * @param string $name
     *
     * @return Config
     */
    public function get($name);

    /**
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function getValue($name, $default = null);

    /**
     * @return array
     */
    public function asArray();
}
