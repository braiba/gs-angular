<?php

namespace Geekstitch\Controllers;

use Geekstitch\Core\Di;
use Geekstitch\Core\View\JsonView;
use Geekstitch\Entity\Category;
use Geekstitch\Entity\CategoryType;
use Geekstitch\Entity\Offer;
use Geekstitch\Entity\Product;
use Geekstitch\Entity\ShippingType;

class OffersController
{
    public function getAction($handle)
    {
        $imageSize = (isset($_GET['image-size']) ? $_GET['image-size'] : null);
        $em = Di::getInstance()->getEntityManager();

        /** @var Offer $offer */
        $offer = $em->getRepository('Geekstitch\\Entity\\Offer')->findOneBy(['handle' => $handle]);
        if ($offer === null) {
            return new JsonView(['error' => 'Pack not found'], 404);
        }

        $data = [
            'name' => $offer->getName(),
            'handle' => $offer->getName(),
            'short_description' => $offer->getShortDescription(),
            'description' => $offer->getDescription(),
            'price' => $offer->getPrice(),
            'link' => $offer->getUrl(),
        ];

        return new JsonView($data);
    }
}
