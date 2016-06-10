<?php

return array(
    'database' => array(
        'host' => '',
        'username' => '',
        'password' => '',
        'schema' => '',
    ),
    'routes' => array(
        'ajax/cart-info' => array(
            'controller' => 'ajax',
            'action' => 'cart-info',
        ),
        'ajax/categories/:handle' => array(
            'controller' => 'ajax',
            'action' => 'category',
        ),
        'ajax/genres' => array(
            'controller' => 'ajax',
            'action' => 'genres',
        ),
        'ajax/fandoms' => array(
            'controller' => 'ajax',
            'action' => 'fandoms',
        ),
        'ajax/offers/:handle' => array(
            'controller' => 'ajax',
            'action' => 'offer',
        ),
        'ajax/packs/:handle' => array(
            'controller' => 'ajax',
            'action' => 'pack',
        ),
        'ajax/shipping-types' => array(
            'controller' => 'ajax',
            'action' => 'shipping-types',
        ),
    ),
);
