<?php

namespace Geekstitch\ImageProcessors;

use Geekstitch\Core\Di;
use Geekstitch\Entity\Image;

/**
 *
 * @author Thomas
 */
abstract class ImageFactory
{

    protected $imagesDir = null;

    /**
     *
     * @return string
     */
    public function getImagesDir()
    {
        if ($this->imagesDir === null) {
            $this->imagesDir = Di::getInstance()->getConfig()->get('images')->getValue('dir', false);
        }

        return $this->imagesDir;
    }

    /**
     *
     * @param string $processData
     *
     * @return Image|null
     */
    protected function getImageByProcessData($processData)
    {
        $entityManager = Di::getInstance()->getEntityManager();

        return $entityManager->getRepository(Image::class)->findOneBy(['processData' => $processData]);
    }

    /**
     *
     * @param string $filename
     * @param string $processData
     *
     * @return Image
     */
    protected function persistImage($filename, $processData)
    {
        $entityManager = Di::getInstance()->getEntityManager();
        $imagesDir = $this->getImagesDir();
        list($width, $height) = getimagesize($imagesDir . $filename);

        $image = new Image();

        $image->setWidth($width);
        $image->setHeight($height);
        $image->setProcessData($processData);
        $image->setPath($filename);
        $image->setImageHash(''); // Can't remember what this field was for now...

        $entityManager->persist($image);
        $entityManager->flush($image);

        return $image;
    }
}
