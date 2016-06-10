<?php
/**
 * Created by PhpStorm.
 * User: Braiba
 * Date: 30/05/2016
 * Time: 22:16
 */

namespace Geekstitch\Entity;

class ShippingType extends AbstractEntity
{
    const ID_UK = 1;

    protected $name;

    protected $handle;

    protected $cost;

    /**
     * @inheritDoc
     */
    public function getTableName()
    {
        return 'shipping_type';
    }

    /**
     * @inheritDoc
     */
    public function getPrimaryKey()
    {
        return 'id';
    }

    public function getHandle()
    {
        return $this->handle;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCost()
    {
        return (float) $this->cost;
    }
}