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

    public function toArray()
    {
        $array = [];

        if ($this->invoiceNumber) {
            $array['INVNUM'] = $this->invoiceNumber;
        }

        if ($this->description) {
            $array['DESC'] = $this->description;
        }

        $itemAmount = 0;
        foreach ($this->items as $i => $item) {
            foreach ($item->toArray() as $key => $value) {
                $array[$key . $i] = $value;
            }

            $itemAmount += $item->cost;
        }

        if ($this->currencyCode) {
            $array['CURRENCYCODE'] = $this->currencyCode;
        }

        $array['ITEMAMT'] = $itemAmount;
        $array['SHIPPINGAMT'] = $this->shippingAmount;
        $array['AMT'] = $itemAmount + $this->shippingAmount;

        return $array;
    }
}
