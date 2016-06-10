<?php

namespace Geekstitch\Core;

use Geekstitch\Core\Config\ArrayConfig;
use Geekstitch\Core\Config\Config;
use Geekstitch\Core\Router\Router;
use Geekstitch\Entity\Basket;
use PDO;
use Slim\PDO\Database;

class Di
{
    /**
     * @var Di
     */
    protected static $instance = null;

    /**
     * @return Di
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Di();
        }
        return self::$instance;
    }

    private function __construct() {}

    protected $application = null;

    /**
     * @return Application
     */
    public function getApplication()
    {
        if ($this->application === null) {
            $this->application = new Application();
        }
        return $this->application;
    }

    protected $basket = null;

    /**
     * @return Basket
     */
    public function getBasket()
    {
        if ($this->basket === null) {
            $this->basket = Basket::getForCurrentUser();
        }
        return $this->basket;
    }

    protected $config = null;

    /**
     * @return Config
     */
    public function getConfig()
    {
        if ($this->config === null) {
            /** @var array $data */
            $data = require ROOT_DIR . 'config/global.config.php';

            $this->config = new ArrayConfig($data);
        }
        return $this->config;
    }

    protected $db = null;

    /**
     * @return PDO
     */
    public function getDb()
    {
        if ($this->db === null) {
            $dbConfig = $this->getConfig()->get('database');

            $host = $dbConfig->getValue('host', 'localhost');
            $schema = $dbConfig->getValue('schema');
            $username = $dbConfig->getValue('username');
            $password = $dbConfig->getValue('password');

            $dsn = 'mysql:host=' . $host . ';dbname=' . $schema;

            $this->db = new Database($dsn, $username, $password);
        }
        return $this->db;
    }

    protected $router = null;

    /**
     * @return Router
     */
    public function getRouter()
    {
        if ($this->router === null) {
            $this->router = new Router();
        }
        return $this->router;
    }
}
