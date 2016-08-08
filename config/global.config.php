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
            'dir' => 'C:\Program Files\ImageMagick\6.8.0-Q16',
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
    'routes' => array(
        'GET cart' => array(
            'controller' => 'cart',
            'action' => 'get',
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
    ),
);
