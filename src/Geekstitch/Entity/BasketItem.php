<?php
/**
 * Created by PhpStorm.
 * User: Braiba
 * Date: 30/05/2016
 * Time: 23:25
 */

namespace Geekstitch\Entity;

/**
 * Class BasketItem
 *
 * @Entity
 * @Table(name="cart_items")
 *
 * @package Geekstitch\Entity
 */
class BasketItem
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(name="cart_item_ID", type="integer")
     *
     * @var int
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="Geekstitch\Entity\Basket", inversedBy="items")
     * @JoinColumn(name="cart_ID", referencedColumnName="cart_ID")
     *
     * @var Basket
     */
    protected $basket;

    /**
     * @ManyToOne(targetEntity="Geekstitch\Entity\Product")
     * @JoinColumn(name="pattern_ID", referencedColumnName="pattern_ID")
     *
     * @var Product
     */
    protected $product;

    /**
     *
     * @Column(type="integer")
     *
     * @var int
     */
    protected $quantity;

    /**
     * @return Basket
     */
    public function getBasket()
    {
        return $this->basket;
    }

    /**
     * @param Basket $basket
     */
    public function setBasket($basket)
    {
        $this->basket = $basket;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @param int $quantity
     */
    public function add($quantity)
    {
        $this->quantity += $quantity;
    }
}