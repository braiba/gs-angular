<?php

namespace Geekstitch\Utils;

class PayPalClient
{
    protected $payPalRedirectUrl;

    protected $apiEndpoint;

    protected $username;

    protected $password;

    protected $version;

    protected $signature;

    /**
     * Constructor
     *
     * @param string $apiEndpoint
     * @param int $version
     * @param string $username
     * @param string $password
     * @param string $signature
     * @param string $payPalRedirectUrl
     * @param string $successCallbackUrl
     * @param string $failureCallbackUrl
     */
    public function __construct(
        $apiEndpoint,
        $version,
        $username,
        $password,
        $signature,
        $payPalRedirectUrl,
        $successCallbackUrl,
        $failureCallbackUrl
    ) {
        $this->apiEndpoint = $apiEndpoint;
        $this->version = $version;
        $this->username = $username;
        $this->password = $password;
        $this->signature = $signature;
        $this->payPalRedirectUrl = $payPalRedirectUrl;
        $this->successCallbackUrl = $successCallbackUrl;
        $this->failureCallbackUrl = $failureCallbackUrl;
    }

    /**
     * Function to perform the API call to PayPal using API signature
     *
     * @param string $methodName
     * @param array $attrs
     *
     * @return array 
     */
    protected function hashCall($methodName, array $attrs)
    {
        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiEndpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //NVPRequest for submitting to server
        $core_attrs = array(
            'METHOD' => $methodName,
            'VERSION' => $this->version,
            'USER' => $this->username,
            'PWD' => $this->password,
            'SIGNATURE' => $this->signature,
            'BUTTONSOURCE' => 'PP-ECWizard', // Only applicable for partners; not sure we need this?
        );
        foreach ($core_attrs as $key=>$value){
            $attrs[$key] = urlencode($value);
        } 
        $pairs = array();
        foreach ($attrs as $key=>$value){
            $pairs[] = $key.'='.$value;
        }
        $request = implode('&', $pairs);

        //setting the nvpreq as POST FIELD to curl
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

        //getting response from server
        $response = curl_exec($ch);

        $nvpResponseArray = self::decodeNvpString($response);

        $errorNo = curl_errno($ch);
        if ($errorNo) {
            $errorMsg = curl_error($ch);

            curl_close($ch);

            throw new \RuntimeException($errorMsg, $errorNo);
        }

        curl_close($ch);

        return $nvpResponseArray;
    }

    /**
     * This function will take NVP string and convert it to an associative array and it will decode the response.
     *   It is useful to search for a particular key and displaying arrays.
     *
     * @param string $nvpStr
     *
     * @return array
     */
    protected static function decodeNvpString($nvpStr)
    {
        $nvpArray = array();
        while (strlen($nvpStr)){
            $keyPos = strpos($nvpStr, '=');
            $valuePos = (strpos($nvpStr, '&') ? strpos($nvpStr, '&') : strlen($nvpStr));

            $key = urldecode(substr($nvpStr, 0, $keyPos));
            $value = urldecode(substr($nvpStr, $keyPos + 1, $valuePos - $keyPos - 1));

            $nvpArray[$key] = $value;

            $nvpStr = substr($nvpStr, $valuePos + 1, strlen($nvpStr));
        }

        return $nvpArray;
    }
    
    /**
     *
     * @param string $token
     *
     * @return string
     */
    public function getPayPalRedirectUrl($token) {
        $queryData = [
            'cmd' => '_express-checkout',
            'token' => $token,
        ];
        $query = http_build_query($queryData);

        return $this->payPalRedirectUrl . '?' . $query;
    }
    
    /**
     * Makes a request to Express Checkout
     * 
     * @param string $paymentType
     * @param string $desc
     * @param string $currencyCodeType
     * 
     * @return boolean 
     */
    public function expressCheckout($paymentType = 'sale', $desc = 'Geek Stitch Order', $currencyCodeType = 'GBP')
    {
        $cart = Cart::getCurrentCart();
        $itemAmount = $cart->getItemCost();
        $shippingAmount = $cart->getShippingCost();
        $invoice_id = $cart->cart_ID;        
        
        $attrs = array(
            'PAYMENTREQUEST_0_AMT' => ($itemAmount + $shippingAmount),
            'PAYMENTREQUEST_0_ITEMAMT' => $itemAmount,
            'PAYMENTREQUEST_0_SHIPPINGAMT' => $shippingAmount,
            'PAYMENTREQUEST_0_PAYMENTACTION' => $paymentType,
            'RETURNURL' => HTMLUtils::absolute($this->successCallbackUrl),
            'CANCELURL' => HTMLUtils::absolute($this->failureCallbackUrl),
            'PAYMENTREQUEST_0_CURRENCYCODE' => $currencyCodeType,
            'PAYMENTREQUEST_0_DESC' => $desc,
            'PAYMENTREQUEST_0_INVNUM' => $invoice_id,
        );
        
        foreach ($cart->CartItems as $i=>$cart_item){
            $pattern = $cart_item->Pattern;
            $attrs['L_PAYMENTREQUEST_0_NAME'.$i] = $pattern->name.' Cross Stitch Pack';
            $attrs['L_PAYMENTREQUEST_0_AMT'.$i] = $pattern->cost;
            $attrs['L_PAYMENTREQUEST_0_QTY'.$i] = $cart_item->quantity;
        }

        $resArray = self::hashCall('SetExpressCheckout', $attrs);

        $ack = strtoupper($resArray['ACK']);
        if ($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING') {
            $token = urldecode($resArray['TOKEN']);
        } else {
            $error = urldecode($resArray['L_LONGMESSAGE0']);
            throw new \RuntimeException($error);
        }

        return $token;
    }

    /**
     * Prepares the parameters for the GetExpressCheckoutDetails API Call
     *
     * @param string $token
     *
     * @return array The NVP Collection object of the GetExpressCheckoutDetails Call Response
     */
    public static function getShippingDetails($token)
    {
        $attrs = array('TOKEN' => $token);
        $response = self::hashCall('GetExpressCheckoutDetails',$attrs);

        $ack = strtoupper($response['ACK']);
        if ($ack !== 'SUCCESS' && $ack !== 'SUCCESSWITHWARNING') {
            $error = urldecode($resArray['L_LONGMESSAGE0']);
            throw new \RuntimeException($error);
        }

        return $response;
    }
}
