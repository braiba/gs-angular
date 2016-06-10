<?php

namespace Geekstitch\Entity;

class Product extends AbstractEntity
{
    protected $name;

    protected $urlChunk;

    protected $cost;

    protected $imageId;

    protected $timestamp;

    public function getTableName()
    {
        return 'patterns';
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->urlChunk;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->cost;
    }
}