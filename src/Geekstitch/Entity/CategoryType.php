<?php

namespace Geekstitch\Entity;

/**
 * Class CategoryType
 *
 * @Entity
 * @Table(name="category_types")
 *
 * @package Geekstitch\Entity
 */
class CategoryType
{
    const ID_FANDOM = 1;
    const ID_GENRE = 2;
    const ID_PRODUCT = 3;
    const ID_LIMITED_EDITION = 4;

    /**
     * @Id
     * @Column(name="category_type_ID", type="integer")
     * @GeneratedValue
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
     * @OneToMany(targetEntity="Geekstitch\Entity\Category", mappedBy="categoryType")
     * @OrderBy({"name"="ASC"})
     *
     * @var string
     */
    protected $categories;

    public function getTableName()
    {
        return 'category_types';
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
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
