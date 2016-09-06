<?php

namespace Geekstitch\Core\View;

class RedirectView extends AbstractView
{
    protected $statusCode;

    protected $path = '/';

    public function __construct($path, $statusCode = 302)
    {
        $this->path = $path;
        $this->statusCode = $statusCode;
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        header('Location: ' . $this->path);

        http_response_code($this->statusCode);
    }
}
