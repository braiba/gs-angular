<?php

namespace Geekstitch\PayPal;

class PayPalOrder
{
    /**
     * @var PayPalPaymentRequest[]
     */
    protected $paymentRequests = [];

    public function addPaymentRequest(PayPalPaymentRequest $paymentRequest)
    {
        $this->paymentRequests[] = $paymentRequest;
    }

    public function toArray()
    {
        $array = [];
        foreach ($this->paymentRequests as $i => $paymentRequest) {
            $array += $paymentRequest->toArray($i);
        }

        return $array;
    }
}
