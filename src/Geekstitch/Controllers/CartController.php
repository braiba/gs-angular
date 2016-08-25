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

    public function patchAction() {
        $rawRequestData = file_get_contents('php://input');

        $requestData = json_decode($rawRequestData, true);
        if ($requestData === false) {
            return new JsonView(['error' => 'Invalid request']);
        }

        $basket = Di::getInstance()->getBasket();

        $em = Di::getInstance()->getEntityManager();

        if (isset($requestData['items'])) {
            $productRepo = $em->getRepository(Product::class);

            $items = $requestData['items'];
            foreach ($items as $item) {
                $productHandle = $item['product'];
                $quantity = $item['quantity'];

                /** @var Product $product */
                $product = $productRepo->findOneBy(['handle' => $productHandle]);
                if ($product === false) {
                    return new JsonView(['error' => 'Unknown product']);
                }

                $basket->setProductQuantity($product, $quantity);
            }
        }

        if (isset($requestData['shippingType'])) {
            /** @var ShippingType $shippingType */
            $shippingType = $em->getRepository(ShippingType::class)
                ->findOneBy(['handle' => $requestData['shippingType']]);
            if ($shippingType === false) {
                return new JsonView(['error' => 'Unknown shipping type']);
            }

            $basket->setShippingType($shippingType);
        }

        $em->persist($basket);
        $em->flush();

        return new JsonView(['success' => true]);
    }
}
