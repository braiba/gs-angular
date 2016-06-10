<?php

namespace Geekstitch\Entity;

/**
 * Class Category
 *
 * @Entity
 * @Table(name="categories")
 *
 * @package Geekstitch\Entity
 */
class Category
{
    /**
     * @Id
     * @Column(name="category_ID", type="integer")
     * @GeneratedValue
     *
     * @var int
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="Geekstitch\Entity\CategoryType")
     * @JoinColumn(name="category_type_ID", referencedColumnName="category_type_ID")
     *
     * @var CategoryType
     */
    protected $categoryType;

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
     * @Column(name="important", type="boolean")
     *
     * @var boolean
     */
    protected $important;

    /**
     * @ManyToOne(targetEntity="Geekstitch\Entity\Product")
     * @JoinColumn(name="sample_pattern_ID", referencedColumnName="pattern_ID")
     *
     * @var Product
     */
    protected $sampleProduct;

    /**
     * @ManyToMany(targetEntity="Geekstitch\Entity\Product", inversedBy="products")
     * @JoinTable(
     *     name="category_membership",
     *     joinColumns={
     *         @JoinColumn(name="category_ID", referencedColumnName="category_ID")
     *     },
     *     inverseJoinColumns={
     *         @JoinColumn(name="pattern_ID", referencedColumnName="pattern_ID")
     *     }
     * )
     *
     * @var Product
     */
    protected $products;

    public function getTableName()
    {
        return 'categories';
    }

    /**
     * @return CategoryType
     */
    public function getCategoryType()
    {
        return $this->categoryType;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return boolean
     */
    public function getImportant()
    {
        return $this->important;
    }

    /**
     * @return Product
     */
    public function getSampleProduct()
    {
        return $this->sampleProduct;
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }
}