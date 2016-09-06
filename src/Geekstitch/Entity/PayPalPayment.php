<?php

namespace Geekstitch\Entity;

/**
 * Class PayPalPayment
 *
 * @Entity
 * @Table(name="paypal_payments")
 *
 * @package Geekstitch\Entity
 */
class PayPalPayment
{
    /**
     * @Id
     * @Column(name="token", type="string")
     *
     * @var string
     */
    public $token;

    /**
     * @Column(name="checkout_status", type="string")
     *
     * @var string
     */
    public $checkoutStatus;

    /**
     * @Column(name="email", type="string")
     *
     * @var string
     */
    public $email;

    /**
     * @Column(name="payer_ID", type="string")
     *
     * @var string
     */
    public $payerId;

    /**
     * @Column(name="payer_status", type="string")
     *
     * @var string
     */
    public $payerStatus;

    /**
     * @Column(name="first_name", type="string")
     *
     * @var string
     */
    public $firstName;

    /**
     * @Column(name="last_name", type="string")
     *
     * @var string
     */
    public $lastName;

    /**
     * @Column(name="ship_name", type="string")
     *
     * @var string
     */
    public $shipName;

    /**
     * @Column(name="ship_street", type="string")
     *
     * @var string
     */
    public $shipStreet;

    /**
     * @Column(name="ship_street_2", type="string")
     *
     * @var string
     */
    public $shipStreet2;

    /**
     * @Column(name="ship_city", type="string")
     *
     * @var string
     */
    public $shipCity;

    /**
     * @Column(name="ship_state", type="string")
     *
     * @var string
     */
    public $shipState;

    /**
     * @Column(name="ship_country", type="string")
     *
     * @var string
     */
    public $shipCountry;

    /**
     * @Column(name="ship_zip", type="string")
     *
     * @var string
     */
    public $shipZip;

    /**
     * @Column(name="address_status", type="string")
     *
     * @var string
     */
    public $addressStatus;

    /**
     * @Column(name="user_message", type="string")
     *
     * @var string
     */
    public $userMessage;
}
