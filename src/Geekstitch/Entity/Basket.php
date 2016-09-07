<?php

namespace Geekstitch\Entity;
use Geekstitch\Core\Di;
use Geekstitch\PayPal\PayPalPaymentRequest;
use Geekstitch\PayPal\PhysicalPayPalPaymentRequestItem;

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
     * @Column(name="session_ID", type="string")
     *
     * @var string
     */
    protected $sessionId;

    /**
     * @OneToMany(targetEntity="Geekstitch\Entity\BasketItem", mappedBy="basket", cascade={"persist"})
     * @OrderBy({"id"="ASC"})
     *
     * @var BasketItem[]
     */
    protected $items = array();

    /**
     * @ManyToOne(targetEntity="Geekstitch\Entity\ShippingType")
     * @JoinColumn(name="shipping_type_ID", referencedColumnName="id")
     *
     * @var ShippingType
     */
    protected $shippingType;

    /**
     * @Column(name="paypal_token", type="string")
     *
     * @var string|null
     */
    protected $payPalToken = null;

    protected $itemQuantity = 0;

    protected $itemTotal = 0.00;

    protected $productIdMap = null;

    /**
     * @return Basket
     */
    public static function getForCurrentUser()
    {
        $em = Di::getInstance()->getEntityManager();

        session_start();

        $sessionId = session_id();

        $basket = $em->getRepository('Geekstitch\\Entity\\Basket')->findOneBy(['sessionId' => $sessionId]);
        if ($basket === null) {
            $basket = new Basket();

            $basket->setSessionId($sessionId);

            /** @var ShippingType $ukShippingType */
            $ukShippingType = $em->find(ShippingType::class, ShippingType::ID_UK);
            $basket->setShippingType($ukShippingType);
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

    public function getProductIdMap()
    {
        if ($this->productIdMap === null) {
            $this->productIdMap = [];

            foreach ($this->items as $index => $item) {
                $this->productIdMap[$item->getProduct()->getId()] = $index;
            }
        }

        return $this->productIdMap;
    }

    /**
     * @param Product $product
     * @param int $quantity
     */
    public function setProductQuantity($product, $quantity = 1)
    {
        $productId = $product->getId();

        $productIdIndex = $this->getProductIdMap();
        if (isset($productIdIndex[$productId])) {
            $index = $productIdIndex[$productId];
            $quantityDiff = $quantity - $this->items[$index]->getQuantity();
            $basketItem = $this->items[$index];

            if ($quantity === 0) {
                $em = Di::getInstance()->getEntityManager();

                unset($this->items[$index]);
                $em->remove($basketItem);
                $em->flush($basketItem);
            } else {
                $basketItem->setQuantity($quantity);
            }
        } else {
            $basketItem = new BasketItem();

            $basketItem->setBasket($this);
            $basketItem->setProduct($product);
            $basketItem->setQuantity($quantity);

            $this->items[] = $basketItem;

            $quantityDiff = $quantity;
        }

        $this->itemQuantity += $quantityDiff;
        $this->itemTotal += $quantityDiff * $product->getPrice();
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
     * @return string
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
     * @return null|string
     */
    public function getPayPalToken()
    {
        return $this->payPalToken;
    }

    /**
     * @param null|string $payPalToken
     */
    public function setPayPalToken($payPalToken)
    {
        $this->payPalToken = $payPalToken;
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

    /**
     *
     * @return PayPalPaymentRequest
     */
    public function generatePayPalPaymentRequest()
    {
        $paymentRequest = new PayPalPaymentRequest();

        foreach ($this->getItems() as $item) {
            $product = $item->getProduct();
            $paymentRequestItem = new PhysicalPayPalPaymentRequestItem(
                '\'' . $product->getName() . '\' cross-stitch pack',
                $product->getPrice(),
                $item->getQuantity()
            );

            $paymentRequest->addPaymentRequestItem($paymentRequestItem);
        }

        $paymentRequest->invoiceNumber = $this->getSessionId();
        $paymentRequest->description = 'Geek Stitch order';
        $paymentRequest->shippingAmount = $this->getShippingType()->getCost();
        $paymentRequest->currencyCode = 'GBP';

        return $paymentRequest;
    }
}