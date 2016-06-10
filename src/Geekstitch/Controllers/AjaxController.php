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

        $shippingType = $basket->getShippingType();

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

    public function categoryAction($handle)
    {
        $em = Di::getInstance()->getEntityManager();

        /** @var Category $category */
        $category = $em->getRepository('Geekstitch\\Entity\\Category')->findOneBy(['handle' => $handle]);
        if ($category === null) {
            return new JsonView(['error' => 'Category not found'], 404);
        }

        $packData = [];
        foreach ($category->getProducts() as $product) {
            $packData[] = [
                'name' => $product->getName(),
                'link' => '#/packs/' . $product->getHandle(),
                'images' => [], //TODO:
            ];
        }

        $offerData = [];
        // TODO: offer data

        $data = [
            'name' => $category->getName(),
            'link' => '#/' . $category->getCategoryType()->getHandle() . '/' . $category->getHandle(),
            'offers' => $offerData,
            'packs' => $packData,
        ];

        return new JsonView($data);
    }

    public function genresAction()
    {
        $data = $this->getCategoryData(CategoryType::ID_GENRE);

        return new JsonView($data);
    }

    public function fandomsAction()
    {
        $data = $this->getCategoryData(CategoryType::ID_FANDOM);

        return new JsonView($data);
    }

    public function offerAction($id)
    {
        $data = array();

        // TODO: get offer from DB

        return new JsonView($data);
    }

    public function packAction($handle)
    {
        $em = Di::getInstance()->getEntityManager();

        /** @var Product $product */
        $product = $em->getRepository('Geekstitch\\Entity\\Product')->findOneBy(['handle' => $handle]);
        if ($product === null) {
            return new JsonView(['error' => 'Pack not found'], 404);
        }

        $categoriesData = [];
        foreach ($product->getCategories() as $category) {
            $categoriesData[] = [
                'name' => $category->getName(),
                'handle' => $category->getHandle(),
                'link' => '#/' . $category->getCategoryType()->getHandle() . '/' . $category->getHandle(),
            ];
        }

        $data = [
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'link' => '#/packs/' . $product->getHandle(),
            'categories' => $categoriesData,
        ];

        return new JsonView($data);
    }

    public function shippingTypesAction()
    {
        $em = Di::getInstance()->getEntityManager();

        /** @var ShippingType[] $shippingTypes */
        $shippingTypes = $em->getRepository('Geekstitch\\Entity\\ShippingType')->findAll();

        $data = [];
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
     *
     * @return array
     */
    protected function getCategoryData($categoryTypeId)
    {
        $importantOnly = (isset($_GET['important']) ? true : false);

        $em = Di::getInstance()->getEntityManager();

        /** @var CategoryType $categoryType */
        $categoryType = $em->find('Geekstitch\\Entity\\CategoryType', $categoryTypeId);
        if ($categoryType === null) {
            return new JsonView(['error' => 'Category type not found'], 404);
        }

        $categoryTypeHandle = $categoryType->getHandle();

        $data = [];
        foreach ($categoryType->getCategories() as $category) {
            if ($importantOnly && !$category->getImportant()) {
                continue;
            }

            $data[$category->getHandle()] = [
                'name' => $category->getName(),
                'handle' => $category->getHandle(),
                'link' => '#/' . $categoryTypeHandle . '/' . $category->getHandle(),
            ];
        }

        return $data;
    }
}
