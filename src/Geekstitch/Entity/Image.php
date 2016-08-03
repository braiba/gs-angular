<?php

namespace Geekstitch\Entity;

use DateTime;
use Geekstitch\Core\Di;

/**
 * Class Image
 *
 * @Entity
 * @Table(name="images")
 *
 * @package Geekstitch\Entity
 */
class Image
{
    /**
     * @Id
     * @Column(name="image_ID", type="integer")
     * @GeneratedValue(strategy="IDENTITY")
     *
     * @var int
     */
    protected $id;

    /**
     * @Column(name="filename", type="string")
     *
     * @var string
     */
    protected $path;

    /**
     * @Column(name="width", type="integer")
     *
     * @var int
     */
    protected $width;

    /**
     * @Column(name="height", type="integer")
     *
     * @var int
     */
    protected $height;

    /**
     * @Column(name="process_data", type="string")
     *
     * @var string
     */
    protected $processData;

    /**
     * @Column(name="image_hash", type="string")
     *
     * @var string
     */
    protected $imageHash;

    /**
     * @Column(name="timestamp", type="datetime")
     *
     * @var DateTime
     */
    protected $timestamp;

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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function getProcessData()
    {
        return $this->processData;
    }

    /**
     * @param string $processData
     */
    public function setProcessData($processData)
    {
        $this->processData = $processData;
    }

    /**
     * @return string
     */
    public function getImageHash()
    {
        return $this->imageHash;
    }

    /**
     * @param string $imageHash
     */
    public function setImageHash($imageHash)
    {
        $this->imageHash = $imageHash;
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
     * @return string
     */
    public function getUrl()
    {
        return Di::getInstance()->getConfig()->get('images')->getValue('path') . $this->getPath();
    }

    /**
     *
     * @return array
     */
    public function getAjaxData()
    {
        return [
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
            'url' => $this->getUrl(),
        ];
    }
}
