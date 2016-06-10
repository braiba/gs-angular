<?php

namespace Geekstitch\Entity;
use Geekstitch\Core\Di;

/**
 * Class Basket
 *
 * @Entity
 * @Table(name="images")
 *
 * @package Geekstitch\Entity
 */
class Image
{
    /**
     * @Id
     * @Column(name="image_ID", type="integer")
     *
     * @var int
     */
    protected $id;
}
