<?php

namespace Geekstitch\ImageProcessors;
use Geekstitch\Entity\Image;

/**
 *
 * @author Thomas
 */
class ImageMagickThumbnailFactory extends ImageMagickProcessingFactory
{

    protected $maxWidth;
    protected $maxHeight;

    /**
     *
     * @param int $maxWidth
     * @param int $maxHeight
     */
    public function __construct($maxWidth, $maxHeight)
    {
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
    }

    /**
     * @return int
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * @return int
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * @inheritDoc
     */
    public function generateTargetProcessData(Image $source)
    {
        $processData = $source->getProcessData();
        $maxWidth = $this->getMaxWidth();
        $maxHeight = $this->getMaxHeight();

        if ($maxHeight < $source->getHeight() || $maxWidth < $source->getWidth()) {
            $processData .= '{resize to: ' . $maxWidth . 'x' . $maxHeight . '}';
        }

        return $processData;
    }

    /**
     * @inheritDoc
     */
    public function generateOutputFilename(Image $source)
    {
        $imagesDir = $this->getImagesDir();
        $maxWidth = $this->getMaxWidth();
        $maxHeight = $this->getMaxHeight();
        $info = pathinfo($source->getPath());

        $dir = $info['dirname'];
        $basename = $info['filename'];
        $ext = $info['extension'];

        return $dir . '/thumbnails/' . $basename .  '_' . $maxWidth . 'x' . $maxHeight . '.' . $ext;
    }

    /**
     * @inheritDoc
     */
    public function getCommand($inputFilename)
    {
        $inputFilename = realpath($inputFilename);

        $maxWidth = $this->getMaxWidth();
        $maxHeight = $this->getMaxHeight();

        return 'convert "' . $inputFilename . '" -resize ' . $maxWidth . 'x' . $maxHeight . ' -';
    }
}
