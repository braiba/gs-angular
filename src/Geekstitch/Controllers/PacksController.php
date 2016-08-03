<?php

namespace Geekstitch\Controllers;

use Geekstitch\Core\Di;
use Geekstitch\Core\View\JsonView;
use Geekstitch\Entity\Category;
use Geekstitch\Entity\CategoryType;
use Geekstitch\Entity\Offer;
use Geekstitch\Entity\Product;
use Geekstitch\Entity\ShippingType;

class PacksController
{
    public function getAction($handle)
    {
        $imageSize = (isset($_GET['image-size']) ? $_GET['image-size'] : null);

        $em = Di::getInstance()->getEntityManager();

        /** @var Product $product */
        $product = $em->getRepository('Geekstitch\\Entity\\Product')->findOneBy(['handle' => $handle]);
        if ($product === null) {
            return new JsonView(['error' => 'Pack not found'], 404);
        }

        $categoriesData = [];
        foreach ($product->getCategories() as $category) {
            $categoriesData[] = $category->getAjaxData();
        }

        $data = $product->getAjaxData($imageSize);

        $data['categories'] = $categoriesData;

        return new JsonView($data);
    }
}
