<?php

namespace Geekstitch\Controllers;

use Geekstitch\Core\Di;
use Geekstitch\Core\View\JsonView;
use Geekstitch\Entity\Category;
use Geekstitch\Entity\CategoryType;
use Geekstitch\Entity\Offer;
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
                    'link' => $product->getUrl(),
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
                'link' => $product->getUrl(),
                'images' => [], //TODO:
            ];
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

    public function genresAction()
    {
        $importantOnly = (isset($_GET['important']) ? true : false);

        $data = $this->getCategoryTypeData(CategoryType::ID_GENRE, $importantOnly);

        return new JsonView($data);
    }

    public function fandomsAction()
    {
        $importantOnly = (isset($_GET['important']) ? true : false);

        $data = $this->getCategoryTypeData(CategoryType::ID_FANDOM, $importantOnly);

        return new JsonView($data);
    }

    public function offerAction($handle)
    {
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

    public function packAction($handle)
    {
        $imageSize = (isset($_GET['imageSize']) ? $_GET['imageSize'] : null);

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
                'link' => $category->getUrl(),
            ];
        }

        $data = [
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'link' => $product->getUrl(),
            'categories' => $categoriesData,
        ];

        if ($imageSize !== null) {
            $image = Di::getInstance()->getImageProcessor()->resize($product->getImage(), $imageSize);
            $data['image'] = $image->getUrl();
        }

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
     * @param boolean $importantOnly
     *
     * @return array
     */
    protected function getCategoryTypeData($categoryTypeId, $importantOnly)
    {
        $em = Di::getInstance()->getEntityManager();

        /** @var CategoryType $categoryType */
        $categoryType = $em->find('Geekstitch\\Entity\\CategoryType', $categoryTypeId);
        if ($categoryType === null) {
            return new JsonView(['error' => 'Category type not found'], 404);
        }

        $data = [
            'name' => $categoryType->getName(),
            'handle' => $categoryType->getHandle(),
            'categories' => [],
        ];
        foreach ($categoryType->getCategories() as $category) {
            if ($importantOnly && !$category->getImportant()) {
                continue;
            }

            $categoryData = [
                'name' => $category->getName(),
                'handle' => $category->getHandle(),
                'link' => $category->getUrl(),
            ];

            $data['categories'][] = $categoryData;
        }

        return $data;
    }
}
