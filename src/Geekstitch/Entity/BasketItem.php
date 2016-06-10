<?php
/**
 * Created by PhpStorm.
 * User: Braiba
 * Date: 30/05/2016
 * Time: 23:25
 */

namespace Geekstitch\Entity;


class BasketItem
{
    protected $product;

    protected $quantity;

    /**
     * @param Product $product
     * @param int $quantity
     */
    public function __construct($product, $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
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
    public function add($quantity)
    {
        $this->quantity += $quantity;
    }
}