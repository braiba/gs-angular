<?php

namespace Geekstitch\PayPal;

class PhysicalPayPalPaymentRequestItem extends PayPalPaymentRequestItem
{
    /**
     * @inheritDoc
     */
    public function toArray($paymentRequestIndex = 0, $itemIndex = 0)
    {
        $prefix = 'L_PAYMENTREQUEST_' . $paymentRequestIndex . '_';
        return [
            $prefix . 'ITEMCATEGORY' . $itemIndex => 'Physical',
            $prefix . 'NAME' . $itemIndex => $this->name,
            $prefix . 'AMT' . $itemIndex => $this->cost,
            $prefix . 'QTY' . $itemIndex => $this->quantity,
        ];
    }
}