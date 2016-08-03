<?php

namespace Geekstitch\Controllers;

use Geekstitch\Core\Di;
use Geekstitch\Core\View\JsonView;
use Geekstitch\Entity\Category;
use Geekstitch\Entity\CategoryType;
use Geekstitch\Entity\Offer;
use Geekstitch\Entity\Product;
use Geekstitch\Entity\ShippingType;

class CategoryTypesController
{
    public function genresListAction()
    {
        $imageSize = (isset($_GET['image-size']) ? $_GET['image-size'] : null);
        $importantOnly = (isset($_GET['important']) ? true : false);

        $data = $this->getCategoryTypeData(CategoryType::ID_GENRE, $imageSize, $importantOnly);

        return new JsonView($data);
    }

    public function fandomsListAction()
    {
        $imageSize = (isset($_GET['image-size']) ? $_GET['image-size'] : null);
        $importantOnly = (isset($_GET['important']) ? true : false);

        $data = $this->getCategoryTypeData(CategoryType::ID_FANDOM, $imageSize, $importantOnly);

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
