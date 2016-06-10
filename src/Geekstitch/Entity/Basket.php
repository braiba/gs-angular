<?php

namespace Geekstitch\Entity;

class Basket
{
    /**
     * @var BasketItem[]
     */
    protected $items = array();

    protected $itemQuantity = 0;

    protected $itemTotal = 0.00;

    /**
     * @var ShippingType
     */
    protected $shippingType;

    /**
     * @return Basket
     */
    public static function getForCurrentUser()
    {
        $basket = new Basket();

        // TODO: load basket from DB
        $product = new Product(1);
        $basket->addProduct($product, 2);
        $basket->setShippingType(new ShippingType(ShippingType::ID_UK));

        return $basket;
    }

    /**
     * @return BasketItem[]
     */
    public function getItems()
    {
        return array_values($this->items);
    }

    /**
     * @param Product $product
     * @param int $quantity
     */
    public function addProduct($product, $quantity = 1)
    {
        $productId = $product->getId();
        if (isset($this->items[$productId])) {
            $this->items[$productId]->add($quantity);
        } else {
            $this->items[$productId] = new BasketItem($product, $quantity);
        }

        $this->itemQuantity += $quantity;
        $this->itemTotal += $product->getPrice();
    }

    /**
     * @return ShippingType
     */
    public function getShippingType()
    {
        return $this->shippingType;
    }

    /**
     * @param ShippingType $shippingType
     */
    public function setShippingType($shippingType)
    {
        $this->shippingType = $shippingType;
    }

    /**
     * @return int
     */
    public function getItemQuantity()
    {
        return $this->itemQuantity;
    }

    /**
     * @return float
     */
    public function getItemTotal()
    {
        return $this->itemTotal;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->itemTotal + $this->getShippingType()->getCost();
    }
}