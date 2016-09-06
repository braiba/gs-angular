<?php

namespace Geekstitch\Entity;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;
use Geekstitch\Core\Di;

/**
 * Class Category
 *
 * @Entity
 * @Table(name="categories")
 *
 * @package Geekstitch\Entity
 */
class Category
{
    /**
     * @Id
     * @Column(name="category_ID", type="integer")
     * @GeneratedValue
     *
     * @var int
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="Geekstitch\Entity\CategoryType")
     * @JoinColumn(name="category_type_ID", referencedColumnName="category_type_ID")
     *
     * @var CategoryType
     */
    protected $categoryType;

    /**
     * @Column(name="name", type="string")
     *
     * @var string
     */
    protected $name;

    /**
     * @Column(name="url_chunk", type="string")
     *
     * @var string
     */
    protected $handle;

    /**
     * @Column(name="important", type="boolean")
     *
     * @var boolean
     */
    protected $important;

    /**
     * @ManyToOne(targetEntity="Geekstitch\Entity\Product")
     * @JoinColumn(name="sample_pattern_ID", referencedColumnName="pattern_ID")
     *
     * @var Product
     */
    protected $sampleProduct;

    /**
     * @ManyToMany(targetEntity="Geekstitch\Entity\Product", inversedBy="products")
     * @JoinTable(
     *     name="category_membership",
     *     joinColumns={
     *         @JoinColumn(name="category_ID", referencedColumnName="category_ID")
     *     },
     *     inverseJoinColumns={
     *         @JoinColumn(name="pattern_ID", referencedColumnName="pattern_ID")
     *     }
     * )
     * @OrderBy({"name"="ASC"})
     *
     * @var Product
     */
    protected $products;

    /**
     * @var Connection
     */
    protected static $connection = null;

    /**
     * @var EntityRepository
     */
    protected static $productRepo = null;

    /**
     * @return EntityRepository
     */
    protected static function getProductRepo()
    {
        if (self::$productRepo === null) {
            $entityManager = Di::getInstance()->getEntityManager();
            self::$connection = $entityManager->getConnection();
            self::$productRepo = $entityManager->getRepository(Product::class);
        }

        return self::$productRepo;
    }

    /**
     * @return Connection
     */
    protected static function getConnection()
    {
        if (self::$connection === null) {
            $entityManager = Di::getInstance()->getEntityManager();
            self::$connection = $entityManager->getConnection();
            self::$productRepo = $entityManager->getRepository(Product::class);
        }

        return self::$connection;
    }

    /**
     * @return CategoryType
     */
    public function getCategoryType()
    {
        return $this->categoryType;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return boolean
     */
    public function getImportant()
    {
        return $this->important;
    }

    /**
     * @return Product
     */
    public function getSampleProduct()
    {
        return $this->sampleProduct;
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return '#/' . $this->getCategoryType()->getHandle() . '/' . $this->getHandle();
    }

    /**
     * @return Product
     */
    public function getRandomProduct()
    {
        $connection = self::getConnection();

        $sql = <<<'SQL'
SELECT
pattern_ID
FROM category_membership
WHERE category_ID = :category_id
ORDER BY RAND()
LIMIT 1
SQL;
        $params = [
            'category_id' => $this->id,
        ];
        $result = $connection->executeQuery($sql, $params);
        $row = $result->fetch();
        if (!$row) {
            return null;
        }

        return self::getProductRepo()->find($row['pattern_ID']);
    }

    /**
     *
     * @param string|null $imageSize
     *
     * @return array
     */
    public function getAjaxData($imageSize = null)
    {
        $data =  [
            'name' => $this->getName(),
            'handle' => $this->getHandle(),
            'link' => $this->getUrl(),
        ];

        if ($imageSize !== null) {
            $imageData = null;

            $product = $this->getRandomProduct();
            if ($product) {
                $thumbnail = $product->getThumbnail($imageSize);
                if ($thumbnail !== null) {
                    $imageData = $thumbnail->getAjaxData();
                }
            }

            $data['image'] = $imageData;
        }

        return $data;
    }
}