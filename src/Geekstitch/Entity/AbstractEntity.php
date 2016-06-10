<?php

namespace Geekstitch\Entity;

use Geekstitch\Core\Di;
use Geekstitch\Utils\Text;

abstract class AbstractEntity
{
    protected $id;

    public function __construct($id = null)
    {
        if ($id !== null) {
            $this->initById($id);
        }
    }

    abstract public function getTableName();

    public function getPrimaryKey()
    {
        return preg_replace('/s$/', '_ID', $this->getTableName());
    }

    protected function initById($id)
    {
        $db = Di::getInstance()->getDb();
        $tableName = $this->getTableName();
        $primaryKey = $this->getPrimaryKey();

        $sql = "SELECT * FROM {$tableName} WHERE {$primaryKey} = {$id}";
        $res = $db->query($sql);

        $row = $res->fetch();
        if ($row === null) {
            throw new \RuntimeException('Not found: ' . get_class($this) . ' #' . $id);
        }

        $this->initFromArray($row);
    }

    public function initFromArray($array) {
        $primaryKey = $this->getPrimaryKey();

        foreach ($array as $key => $value) {
            if ($key === $primaryKey) {
                $key = 'id';
            }
            $key = Text::stubToCamelCase($key);
            $this->{$key} = $value;
        }
    }
}