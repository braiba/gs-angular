<?php

namespace Geekstitch\Controllers;

use Geekstitch\Core\Di;
use Geekstitch\Core\View\JsonView;
use Geekstitch\Entity\Category;
use Geekstitch\Entity\CategoryType;
use Geekstitch\Entity\Offer;
use Geekstitch\Entity\Product;
use Geekstitch\Entity\ShippingType;

class CartController
{
    /**
     * @return JsonView
     */
    public function getAction()
    {
        $imageSize = (isset($_GET['image-size']) ? $_GET['image-size'] : null);

        $basket = Di::getInstance()->getBasket();

        $itemData = array();
        foreach ($basket->getItems() as $item) {
            $product = $item->getProduct();

            $itemData[] = [
                'product' => $product->getAjaxData($imageSize),
                'quantity' => $item->getQuantity()
            ];
        }

        $shippingType = $basket->getShippingType();

        $data = [
            'items' => $itemData,
            'shippingType' => $shippingType->getHandle(),
        ];

        return new JsonView($data);
    }
}
