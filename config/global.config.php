<?php

return array(
    'database' => array(
        'host' => '',
        'username' => '',
        'password' => '',
        'schema' => '',
    ),
    'images' => [
        'imageMagick' => [
            'dir' => '',
        ],
        'dir' => ROOT_DIR . 'public/img/',
        'path' => 'img/',
        'sizes' => [
            'browse_thumbnail' => [
                'width' => 100,
                'height' => 100,
            ],
            'cart_thumbnail' => [
                'width' => 100,
                'height' => 100,
            ],
            'pack_page_main' => [
                'width' => 400,
                'height' => 400,
            ],
        ],
    ],
    'paypal' => array(
        'apiEndpoint' => 'https://api-3t.paypal.com/nvp',
        'payPalRedirectUrl' => 'https://www.paypal.com/cgi-bin/webscr',
        'successCallbackUrl' => '/pay-pal/success',
        'failureCallbackUrl' => '/pay-pal/failure',
        'username' => 'contactus_api1.geekstitch.co.uk',
        'password' => 'AL2Y35UPE5YYESD8',
        'signature' => 'ARJgxv7P5ty.F5oRDHterBK.w8tZArDf4D5YAQbDENfykkFMnrL2WeRJ',
        'version' => 64,
    ),
    'site' => [
        'debug' => false,
        'path' => '',
        'baseUrl' => '',
    ],
    'routes' => array(
        'GET cart' => array(
            'controller' => 'cart',
            'action' => 'get',
        ),
        'PATCH cart' => array(
            'controller' => 'cart',
            'action' => 'patch',
        ),
        'GET categories/:handle' => array(
            'controller' => 'categories',
            'action' => 'get',
        ),
        'GET genres' => array(
            'controller' => 'category-types',
            'action' => 'genres-list',
        ),
        'GET fandoms' => array(
            'controller' => 'category-types',
            'action' => 'fandoms-list',
        ),
        'GET offers/:handle' => array(
            'controller' => 'offers',
            'action' => 'get',
        ),
        'GET packs/:handle' => array(
            'controller' => 'packs',
            'action' => 'get',
        ),
        'GET shipping-types' => array(
            'controller' => 'shipping-types',
            'action' => 'list',
        ),
        'POST checkout/payment' => array(
            'controller' => 'pay-pal',
            'action' => 'payment',
        ),
        'GET pay-pal/success' => array(
            'controller' => 'pay-pal',
            'action' => 'success',
        ),
        'GET pay-pal/failure' => array(
            'controller' => 'pay-pal',
            'action' => 'failure',
        ),
    ),
);
