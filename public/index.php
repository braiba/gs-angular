<?php

use Geekstitch\Core\Di;

define('ROOT_DIR', realpath(__DIR__.'/../') .DIRECTORY_SEPARATOR);

require ROOT_DIR . 'vendor/autoload.php';

Di::getInstance()->getApplication()->handle($_GET['route']);
