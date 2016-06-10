<?php

namespace Geekstitch\Core;

use Geekstitch\Core\Controller\Controller;
use Geekstitch\Core\View\View;
use Geekstitch\Utils\Text;

class Application
{
    public function handle($route)
    {
        $di = Di::getInstance();

        $route = $di->getRouter()->getRoute($route);
        if ($route === null) {
            header('HTTP/1.0 404 Not Found');
            return;
        }

        $controller = $this->getController($route->getController());

        $view = $this->callAction($controller, $route->getAction(), $route->getId());

        echo $view->render();
    }

    /**
     * @param string $name
     *
     * @return Controller
     */
    protected function getController($name)
    {
        $className = 'Geekstitch\\Controllers\\' . Text::stubToPascalCase($name) . 'Controller';
        if (!class_exists($className)) {
            return null;
        }

        return new $className;
    }

    /**
     * @param Controller $controller
     * @param string $actionName
     * @param int|null $id
     *
     * @return View
     */
    protected function callAction($controller, $actionName, $id = null)
    {
        $methodName = Text::stubToCamelCase($actionName) . 'Action';

        return $controller->{$methodName}($id);
    }
}
