<?php

namespace Geekstitch\ImageProcessors;

use Geekstitch\Entity\Image;

/**
 *
 * @author Thomas
 */
abstract class ImageProcessingFactory extends ImageFactory
{
    /**
     *
     * @param Image $source
     *
     * @return string
     */
    protected abstract function generateTargetProcessData(Image $source);

    /**
     *
     * @param Image $source
     *
     * @return string
     */
    protected abstract function generateProcessedImage(Image $source);

    /**
     *
     * @param Image $source the input image
     *
     * @return Image the processed image
     */
    public function getProcessedImage(Image $source)
    {
        $targetProcessData = $this->generateTargetProcessData($source);

        $image = $this->getImageByProcessData($targetProcessData);
        if ($image !== null) {
            return $image;
        }

        $filename = $this->generateProcessedImage($source);

        return $this->persistImage($filename, $targetProcessData);
    }
}
