<?php

namespace Geekstitch\Entity;

use DateTime;

/**
 * Class Product
 *
 * @Entity
 * @Table(name="patterns")
 *
 * @package Geekstitch\Entity
 */
class Product
{
    /**
     * @Id
     * @Column(name="pattern_ID", type="integer")
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
     * @Column(name="cost", type="float")
     *
     * @var float
     */
    protected $price;

    /**
     * @Column(name="timestamp", type="datetime")
     *
     * @var DateTime
     */
    protected $timestamp;

    /**
     * @ManyToOne(targetEntity="Geekstitch\Entity\Image")
     * @JoinColumn(name="image_ID", referencedColumnName="image_ID")
     *
     * @var Image
     */
    protected $image;

    /**
     * @ManyToMany(targetEntity="Geekstitch\Entity\Category", mappedBy="products")
     *
     * @var Category
     */
    protected $categories;

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
        return $this->handle;
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
        return $this->price;
    }

    /**
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }
}