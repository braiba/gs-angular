<?php

namespace Geekstitch\Controllers;

use Geekstitch\Core\Di;
use Geekstitch\Core\View\JsonView;
use Geekstitch\Entity\Category;
use Geekstitch\Entity\CategoryType;
use Geekstitch\Entity\Product;
use Geekstitch\Entity\ShippingType;

class AjaxController
{
    /**
     * @return JsonView
     */
    public function cartInfoAction()
    {
        $basket = Di::getInstance()->getBasket();

        $shippingType = $basket->getShippingType();

        $itemData = array();
        foreach ($basket->getItems() as $item) {
            $product = $item->getProduct();
            $handle = $product->getHandle();

            $itemData[] = array(
                'product' => array(
                    'handle' => $handle,
                    'name' => $product->getName(),
                    'link' => '#/pack/' . $handle,
                    'price' => $product->getPrice(),
                ),
                'quantity' => $item->getQuantity()
            );
        }

        $data = array(
            'items' => $itemData,
            'itemTotal' => $basket->getItemTotal(),
            'shipping' => [
                'handle' => $shippingType->getHandle(),
                'name' => $shippingType->getName(),
                'cost' => $shippingType->getCost(),
            ],
            'count' => $basket->getItemQuantity(),
            'totalCost' => $basket->getTotal(),
        );

        return new JsonView($data);
    }

    public function categoryAction($categoryId)
    {
        $category = new Category($categoryId);

        $offerData = [];

        $db = Di::getInstance()->getDb();

        $sql = <<<SQL
SELECT
patterns.*
FROM patterns
JOIN category_membership USING (pattern_ID)
WHERE category_ID = {$categoryId}
SQL;
        $res = $db->query($sql);

        $packData = [];
        while ($row = $res->fetch()) {
            $product = new Product();
            $product->initFromArray($row);
            $packData[] = [
                'name' => $product->getName(),
                'link' => '#/offer/' . $product->getHandle(),
                'images' => [], //TODO:
            ];
        }

        $data = [
            'name' => $category->getName(),
            'link' => null, // TODO:
            'offers' => $offerData,
            'packs' => $packData,
        ];

        // TODO: get genres from DB

        return new JsonView($data);
    }

    public function genresAction()
    {
        $data = $this->getCategoryData(CategoryType::ID_GENRE, 'genres');

        return new JsonView($data);
    }

    public function fandomsAction()
    {
        $data = $this->getCategoryData(CategoryType::ID_FANDOM, 'fandoms');

        return new JsonView($data);
    }

    public function offerAction($id)
    {
        $data = array();

        // TODO: get offer from DB

        return new JsonView($data);
    }

    public function packAction($id)
    {
        $data = array();

        // TODO: get pack from DB

        return new JsonView($data);
    }

    public function shippingTypesAction()
    {
        // TODO:

        /** @var ShippingType[] $shippingTypes */
        $shippingTypes = [
            new ShippingType(1),
            new ShippingType(2),
            new ShippingType(3),
        ];

        $data = array();

        foreach ($shippingTypes as $shippingType) {
            $data[$shippingType->getHandle()] = [
                'name' => $shippingType->getName(),
                'cost' => $shippingType->getCost(),
            ];
        }

        return new JsonView($data);
    }

    /**
     * @param int $categoryTypeId
     * @param string $categoryTypeHandle
     *
     * @return array
     */
    protected function getCategoryData($categoryTypeId, $categoryTypeHandle)
    {
        $important = (isset($_GET['important']) ? 1 : 0);

        $sql = <<<SQL
SELECT *
FROM categories
WHERE category_type_id = {$categoryTypeId}
AND (important OR NOT {$important})
ORDER BY name;
SQL;
        $res = Di::getInstance()->getDb()->query($sql);

        $data = [];
        while ($row = $res->fetch()) {
            $category = new Category();
            $category->initFromArray($row);

            $data[$category->getHandle()] = [
                'name' => $category->getName(),
                'link' => '#/' . $categoryTypeHandle . '/' . $category->getHandle(),
            ];
        }
        return $data;
    }
}
