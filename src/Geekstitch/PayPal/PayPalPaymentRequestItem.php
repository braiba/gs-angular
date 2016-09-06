<?php

namespace Geekstitch\PayPal;

abstract class PayPalPaymentRequestItem
{
    public $name;

    public $cost;

    public $quantity;

    public function __construct($name, $cost, $quantity)
    {
        $this->name = $name;
        $this->cost = $cost;
        $this->quantity = $quantity;
    }

    /**
     *
     * @return array
     */
    public abstract function toArray();
}
