<?php

namespace Geekstitch\PayPal;

class PhysicalPayPalPaymentRequestItem extends PayPalPaymentRequestItem
{
    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            'ITEMCATEGORY' => 'Physical',
            'NAME' => $this->name,
            'AMT' => $this->cost,
            'QTY' => $this->quantity,
        ];
    }
}