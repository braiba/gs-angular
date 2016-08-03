<?php

namespace Geekstitch\Utils;

class TextUtils
{
    private function __construct() {}

    /**
     * @param string $stub
     *
     * @return string
     */
    public static function stubToCamelCase($stub)
    {
        return lcfirst(str_replace(' ', '', ucwords(preg_replace('/[^a-z0-9]+/i', ' ', $stub))));
    }

    /**
     * @param string $stub
     *
     * @return string
     */
    public static function stubToPascalCase($stub)
    {
        return str_replace(' ', '', ucwords(preg_replace('/[^a-z0-9]+/i', ' ', $stub)));
    }
}