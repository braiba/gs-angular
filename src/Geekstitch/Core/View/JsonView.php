<?php

namespace Geekstitch\Core\View;

class JsonView extends AbstractView
{
    protected $data = array();

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        header('Content-Type: application/json');

        return json_encode($this->data);
    }
}
