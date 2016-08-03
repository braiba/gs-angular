<?php

namespace Geekstitch\Core\Router;

use Geekstitch\Core\Config\NullConfig;
use Geekstitch\Core\Di;

class Router
{
    /**
     * @param string $path
     * @param string $method
     *
     * @return Route
     */
    public function getRoute($path, $method)
    {
        $config = Di::getInstance()->getConfig();

        $routeString = strtoupper($method) . ' ' . $path;
        $params = [];

        $count = 0;
        $matchingConfig = null;
        foreach ($config->get('routes') as $pattern => $routeConfig) {
            $regex = '#' . str_replace(':handle', '([a-z0-9-]+)', $pattern, $count) . '#';
            $hasHandle = ($count !== 0);

            if (preg_match($regex, $routeString, $matches)) {
                $matchingConfig = $routeConfig;
                if ($hasHandle) {
                    $params[] = $matches[1];
                }
                break;
            }
        }

        if ($matchingConfig === null) {
            return null;
        }

        $controller = $matchingConfig->getValue('controller', 'default');
        $action = $matchingConfig->getValue('action', 'default');

        return new Route($controller, $action, $params);
    }
}
