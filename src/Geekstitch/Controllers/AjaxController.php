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
            'itemTotal' => $basket->getItemTotal(),
            'shipping' => $shippingType->getAjaxData(),
            'count' => $basket->getItemQuantity(),
            'totalCost' => $basket->getTotal(),
        ];

        return new JsonView($data);
    }

    public function categoryAction($handle)
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

    public function genresAction()
    {
        $imageSize = (isset($_GET['image-size']) ? $_GET['image-size'] : null);
        $importantOnly = (isset($_GET['important']) ? true : false);

        $data = $this->getCategoryTypeData(CategoryType::ID_GENRE, $imageSize, $importantOnly);

        return new JsonView($data);
    }

    public function fandomsAction()
    {
        $imageSize = (isset($_GET['image-size']) ? $_GET['image-size'] : null);
        $importantOnly = (isset($_GET['important']) ? true : false);

        $data = $this->getCategoryTypeData(CategoryType::ID_FANDOM, $imageSize, $importantOnly);

        return new JsonView($data);
    }

    public function offerAction($handle)
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

    public function packAction($handle)
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

    public function shippingTypesAction()
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

    /**
     * @param int $categoryTypeId
     * @param string $imageSize
     * @param boolean $importantOnly
     *
     * @return array
     */
    protected function getCategoryTypeData($categoryTypeId, $imageSize, $importantOnly)
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

            $data['categories'][] = $category->getAjaxData($imageSize);
        }

        return $data;
    }
}
