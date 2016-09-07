<?php

namespace Geekstitch\PayPal;

class PayPalPaymentRequest
{
    /**
     * @var string|null
     */
    public $invoiceNumber = null;

    /**
     * @var string|null
     */
    public $description = null;

    /**
     * @var string|null
     */
    public $currencyCode = null;

    /**
     * @var float
     */
    public $shippingAmount = 0;

    /**
     * @var PayPalPaymentRequestItem[]
     */
    protected $items = [];

    public function addPaymentRequestItem(PayPalPaymentRequestItem $requestItem)
    {
        $this->items[] = $requestItem;
    }

    public function toArray($paymentRequestIndex = 0)
    {
        $array = [];

        $prefix = 'PAYMENTREQUEST_' . $paymentRequestIndex . '_';

        if ($this->invoiceNumber) {
            $array[$prefix . 'INVNUM'] = $this->invoiceNumber;
        }

        if ($this->description) {
            $array[$prefix . 'DESC'] = $this->description;
        }

        $itemAmount = 0;
        foreach ($this->items as $itemIndex => $item) {
            $array += $item->toArray($paymentRequestIndex, $itemIndex);

            $itemAmount += $item->cost;
        }

        if ($this->currencyCode) {
            $array[$prefix . 'CURRENCYCODE'] = $this->currencyCode;
        }

        $array[$prefix . 'ITEMAMT'] = $itemAmount;
        $array[$prefix . 'SHIPPINGAMT'] = $this->shippingAmount;
        $array[$prefix . 'AMT'] = $itemAmount + $this->shippingAmount;

        return $array;
    }
}
