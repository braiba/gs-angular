<?php

use Geekstitch\Core\Di;

define('ROOT_DIR', realpath(__DIR__.'/../') .DIRECTORY_SEPARATOR);

require ROOT_DIR . 'vendor/autoload.php';

function errorHandler()
{
    $last_error = error_get_last();
    if ($last_error && ($last_error['type'] & (E_ERROR | E_PARSE)))
    {
        header("HTTP/1.1 500 Internal Server Error");
    }
}

register_shutdown_function('errorHandler');

Di::getInstance()->getApplication()->handle($_GET['route'], $_SERVER['REQUEST_METHOD']);
