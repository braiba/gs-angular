<?php

namespace Geekstitch\Entity;

use DateTime;
use Geekstitch\Core\Di;
use Geekstitch\ImageProcessors\ImageMagickThumbnailFactory;

/**
 * Class Product
 *
 * @Entity
 * @Table(name="patterns")
 *
 * @package Geekstitch\Entity
 */
class Product
{
    /**
     * @Id
     * @Column(name="pattern_ID", type="integer")
     *
     * @var int
     */
    protected $id;

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
     * @Column(name="cost", type="float")
     *
     * @var float
     */
    protected $price;

    /**
     * @Column(name="timestamp", type="datetime")
     *
     * @var DateTime
     */
    protected $timestamp;

    /**
     * @ManyToOne(targetEntity="Geekstitch\Entity\Image")
     * @JoinColumn(name="image_ID", referencedColumnName="image_ID")
     *
     * @var Image
     */
    protected $image;

    /**
     * @ManyToMany(targetEntity="Geekstitch\Entity\Category", mappedBy="products")
     * @OrderBy({"name"="ASC"})
     *
     * @var Category[]
     */
    protected $categories;

    protected static $thumbnailFactories = [];

    /**
     *
     * @param string $size
     *
     * @return ImageMagickThumbnailFactory
     */
    protected static function getThumbnailFactory($size)
    {
        if (!array_key_exists($size, self::$thumbnailFactories)) {
            $sizeConfig = Di::getInstance()->getConfig()->get('images')->get('sizes')->get($size);

            $width = $sizeConfig->getValue('width');
            $height = $sizeConfig->getValue('height');

            self::$thumbnailFactories[$size] = new ImageMagickThumbnailFactory($width, $height);
        }

        return self::$thumbnailFactories[$size];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param string $handle
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param DateTime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Image $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return '#/packs/' . $this->getHandle();
    }

    /**
     *
     * @param string $size
     *
     * @return Image
     */
    public function getThumbnail($size)
    {
        $thumbnailFactory = self::getThumbnailFactory($size);
        if (!$thumbnailFactory) {
            return null;
        }

        return $thumbnailFactory->getProcessedImage($this->getImage());
    }

    /**
     *
     * @param string|null $imageSize
     *
     * @return array
     */
    public function getAjaxData($imageSize = null)
    {
        $data = [
            'handle' => $this->getHandle(),
            'name' => $this->getName(),
            'link' => $this->getUrl(),
            'price' => $this->getPrice(),
        ];

        if ($imageSize !== null) {
            $data['image'] = $this->getThumbnail($imageSize)->getAjaxData();
        }

        return $data;
    }
}