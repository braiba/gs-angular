<?php

namespace Geekstitch\Core\Router;

use Geekstitch\Core\Config\NullConfig;
use Geekstitch\Core\Di;

class Router
{
    /**
     * @param string $path
     *
     * @return Route
     */
    public function getRoute($path)
    {
        $config = Di::getInstance()->getConfig();

        $count = 0;
        $pathPattern = preg_replace('/[0-9]+/', ':id', $path, 1, $count);
        $hasParams =

        $routeConfig = $config->get('routes')->get($pathPattern);
        if ($routeConfig instanceof NullConfig) {
            return null;
        }

        $id = null;
        if ($count !== 0) {
            $id = intval(preg_replace('/[^0-9]+/', '', $path));
        }

        return new Route(
            $routeConfig->getValue('controller', 'default'),
            $routeConfig->getValue('action', 'default'),
            $params
        );
    }
}
