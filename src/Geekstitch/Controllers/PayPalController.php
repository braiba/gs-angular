<?php

namespace Geekstitch\Controllers;

use Geekstitch\Core\Di;
use Geekstitch\Core\View\JsonView;
use Geekstitch\Core\View\RedirectView;
use Geekstitch\Entity\Basket;
use Geekstitch\Entity\PayPalPayment;
use Geekstitch\PayPal\PayPalOrder;

class PayPalController
{
    /**
     * The action which redirects the user to PayPal
     *
     * @return array response array
     */
    public function paymentAction()
    {
        $di = Di::getInstance();

        $basket = $di->getBasket();
        $payPalClient = $di->getPayPalClient();

        try {
            $order = new PayPalOrder();

            $order->addPaymentRequest($basket->generatePayPalPaymentRequest());

            $token = $payPalClient->expressCheckout($order);
        } catch (\Exception $e) {
            $responseData = [
                'success' => false,
            ];
            if ($di->getConfig()->get('site')->getValue('debug')) {
                $responseData['error'] = $e->getMessage();
            }
            return new JsonView($responseData);
        }

        return new JsonView([
            'success' => true,
            'redirect' => $payPalClient->getPayPalRedirectUrl($token),
        ]);
    }

    /**
     * The action PayPal will redirect to after a successful payment
     */
    public function successAction()
    {
        $token = $_REQUEST['token'];
        error_log('PayPal Token: '.$token); // Throw this to the error log, just in case

        $di = Di::getInstance();
        $payPalClient = $di->getPayPalClient();

        $shippingDetails = $payPalClient->getShippingDetails($token);

        error_log(print_r($shippingDetails,true)); // Also just in case

        $em = $di->getEntityManager();

        $sessionId = $shippingDetails['PAYMENTREQUEST_0_INVNUM'];

        if (!session_id()) {
            session_start();
        }

        if ($sessionId === session_id()) {
            session_regenerate_id(true);
        }

        /** @var Basket $basket */
        $basket = $em->getRepository(Basket::class)->findOneBy(['sessionId' => $sessionId]);

        $basket->setPayPalToken($token);

        $em->persist($basket);
        $em->flush($basket);

        /** @var PayPalPayment $payment */
        $payment = $em->getRepository(PayPalPayment::class)->findOneBy(['token' => $token]);
        if ($payment === null) {
            $payment = new PayPalPayment();

            $payment->token = $shippingDetails['TOKEN'];
            $payment->checkoutStatus = $shippingDetails['CHECKOUTSTATUS'];
            $payment->email = $shippingDetails['EMAIL'];
            $payment->payerId = $shippingDetails['PAYERID'];
            $payment->payerStatus = $shippingDetails['PAYERSTATUS'];
            $payment->firstName = $shippingDetails['FIRSTNAME'];
            $payment->lastName = $shippingDetails['LASTNAME'];
            $payment->shipName = $shippingDetails['SHIPTONAME'];
            $payment->shipStreet = $shippingDetails['SHIPTOSTREET'];
            if (isset($shippingDetails['SHIPTOSTREET2'])) {
                $payment->shipStreet2 = $shippingDetails['SHIPTOSTREET2'];
            }
            $payment->shipCity = $shippingDetails['SHIPTOCITY'];
            $payment->shipState = $shippingDetails['SHIPTOSTATE'];
            $payment->shipCountry = $shippingDetails['SHIPTOCOUNTRYCODE'];
            $payment->shipZip = $shippingDetails['SHIPTOZIP'];
            $payment->addressStatus = $shippingDetails['ADDRESSSTATUS'];
            if (isset($shippingDetails['PAYMENTREQUEST_0_NOTETEXT'])) {
                $payment->userMessage = $shippingDetails['PAYMENTREQUEST_0_NOTETEXT'];
            }

            $em->persist($payment);
            $em->flush($payment);

            $items = array();
            foreach ($basket->getItems() as $item){
                $items[] = $item->getQuantity().' x '.$item->getProduct()->getName();
            }
            $address = array(
                $payment->shipName,
                $payment->shipStreet,
                $payment->shipStreet2,
                $payment->shipCity,
                $payment->shipState,
                $payment->shipCountry,
                $payment->shipZip,
            );
            $message = implode("\r\n",$items)."\r\n\r\n".implode("\r\n",$address);

            $toAddress = $di->getConfig()->get('notifications')->getValue('order');
            if ($toAddress) {
                mail($toAddress, 'Geekstitch Order', $message, 'From: contactus@geekstitch.co.uk');
            }
        }
        
        $res = $payPalClient->confirmPayment($token, $shippingDetails['PAYERID'], $shippingDetails['AMT']);
        error_log(print_r($res,true)); // Also just in case

        $baseUrl = Di::getInstance()->getConfig()->get('site')->getValue('baseUrl');

        return new RedirectView($baseUrl . '/#/thank-you');
    }

    /**
     * The action PayPal will redirect to after a failed (/aborted?) payment attempt
     */
    public function failureAction()
    {
        $baseUrl = Di::getInstance()->getConfig()->get('site')->getValue('baseUrl');

        return new RedirectView($baseUrl . '/#/sorry');
    }
}

?>