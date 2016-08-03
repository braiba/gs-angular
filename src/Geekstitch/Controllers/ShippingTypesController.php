<?php

namespace Geekstitch\Controllers;

use Geekstitch\Core\Di;
use Geekstitch\Core\View\JsonView;
use Geekstitch\Entity\Category;
use Geekstitch\Entity\CategoryType;
use Geekstitch\Entity\Offer;
use Geekstitch\Entity\Product;
use Geekstitch\Entity\ShippingType;

class ShippingTypesController
{
    public function listAction()
    {
        $em = Di::getInstance()->getEntityManager();

        /** @var ShippingType[] $shippingTypes */
        $shippingTypes = $em->getRepository('Geekstitch\\Entity\\ShippingType')->findAll();

        $data = [];
        foreach ($shippingTypes as $shippingType) {
            $data[$shippingType->getHandle()] = $shippingType->getAjaxData();
        }

        return new JsonView($data);
    }
}
