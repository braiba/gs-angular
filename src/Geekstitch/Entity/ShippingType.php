<?php

namespace Geekstitch\Entity;

/**
 * Class ShippingType
 *
 * @Entity
 * @Table(name="shipping_type")
 *
 * @package Geekstitch\Entity
 */
class ShippingType
{
    const ID_UK = 1;

    /**
     * @Id
     * @Column(name="id", type="integer")
     *
     * @var int
     */
    protected $id;

    /**
     * @Column(name="name", type="string")
     *
     * @var string
     */
    protected $name;

    /**
     * @Column(name="handle", type="string")
     *
     * @var string
     */
    protected $handle;

    /**
     * @Column(name="cost", type="float")
     *
     * @var float
     */
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

    /**
     *
     * @return array
     */
    public function getAjaxData()
    {
        return [
            'handle' => $this->getHandle(),
            'name' => $this->getName(),
            'cost' => $this->getCost(),
        ];
    }
}