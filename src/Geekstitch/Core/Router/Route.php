<?php

namespace Geekstitch\Core\Router;

class Route
{
    protected $controller;

    protected $action;

    protected $id;

    public function __construct($controller, $action, $id = null)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}