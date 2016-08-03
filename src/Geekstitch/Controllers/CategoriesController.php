<?php

namespace Geekstitch\Controllers;

use Geekstitch\Core\Di;
use Geekstitch\Core\View\JsonView;
use Geekstitch\Entity\Category;
use Geekstitch\Entity\CategoryType;
use Geekstitch\Entity\Offer;
use Geekstitch\Entity\Product;
use Geekstitch\Entity\ShippingType;

class CategoriesController
{
    public function getAction($handle)
    {
        $imageSize = (isset($_GET['image-size']) ? $_GET['image-size'] : null);

        $em = Di::getInstance()->getEntityManager();

        /** @var Category $category */
        $category = $em->getRepository('Geekstitch\\Entity\\Category')->findOneBy(['handle' => $handle]);
        if ($category === null) {
            return new JsonView(['error' => 'Category not found'], 404);
        }

        $packData = [];
        foreach ($category->getProducts() as $product) {
            $packData[] = $product->getAjaxData($imageSize);
        }

        $offerData = [];
        // TODO: offer data

        $data = [
            'name' => $category->getName(),
            'link' => $category->getUrl(),
            'offers' => $offerData,
            'packs' => $packData,
        ];

        return new JsonView($data);
    }
}
