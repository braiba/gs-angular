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
            foreach ($paymentRequest->toArray() as $key => $value) {
                $array['PAYMENTREQUEST_' . $i . '_' . $key] = $value;
            }
        }

        return $array;
    }
}
