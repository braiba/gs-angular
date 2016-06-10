<?php

namespace Geekstitch\Entity;
use Geekstitch\Core\Di;

/**
 * Class Basket
 *
 * @Entity
 * @Table(name="carts")
 *
 * @package Geekstitch\Entity
 */
class Basket
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(name="cart_ID", type="integer")
     *
     * @var int
     */
    protected $id;

    /**
     * @Column(name="session_ID", type="integer")
     *
     * @var int
     */
    protected $sessionId;

    /**
     * @OneToMany(targetEntity="Geekstitch\Entity\BasketItem", mappedBy="basket", cascade={"persist"})
     *
     * @var BasketItem[]
     */
    protected $items = array();

    protected $itemQuantity = 0;

    protected $itemTotal = 0.00;

    /**
     * @ManyToOne(targetEntity="Geekstitch\Entity\ShippingType")
     * @JoinColumn(name="shipping_type_ID", referencedColumnName="id")
     *
     * @var ShippingType
     */
    protected $shippingType;

    /**
     * @return Basket
     */
    public static function getForCurrentUser()
    {
        $em = Di::getInstance()->getEntityManager();

        $sessionId = session_id();
        // DEBUG
        $sessionId = 'debug';

        $basket = $em->getRepository('Geekstitch\\Entity\\Basket')->findOneBy(['sessionId' => $sessionId]);
        if ($basket === null) {
            $basket = new Basket();

            $basket->setSessionId($sessionId);
        }

        return $basket;
    }

    /**
     * @return BasketItem[]
     */
    public function getItems()
    {
        return $this->items;
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
            $basketItem = new BasketItem();

            $basketItem->setBasket($this);
            $basketItem->setProduct($product);
            $basketItem->setQuantity($quantity);

            $this->items[$productId] = $basketItem;
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
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
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