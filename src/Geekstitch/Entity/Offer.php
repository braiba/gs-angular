<?php

namespace Geekstitch\Entity;

use DateTime;

/**
 * Class Offer
 *
 * @Entity
 * @Table(name="offers")
 *
 * @package Geekstitch\Entity
 */
class Offer
{
    /**
     * @Id
     * @Column(name="offer_ID", type="integer")
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
     * @Column(name="url_chunk", type="string")
     *
     * @var string
     */
    protected $handle;

    /**
     * @Column(name="short_description", type="string")
     *
     * @var string
     */
    protected $shortDescription;

    /**
     * @Column(name="description", type="string")
     *
     * @var string
     */
    protected $description;

    /**
     * @Column(name="cost", type="float")
     *
     * @var float
     */
    protected $price;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param string $handle
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return '#/offers/' . $this->getHandle();
    }
}