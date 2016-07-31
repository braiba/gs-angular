<?php

namespace Geekstitch\ImageProcessors\Handlers\Resize;

use Geekstitch\Core\Config\Config;
use Geekstitch\Core\Di;
use Geekstitch\Entity\Image;
use Geekstitch\ImageProcessors\Handlers\AbstractImageMagickHandler;
use Geekstitch\ImageProcessors\Handlers\ResizeHandler;

class ImageMagickResizeHandler extends AbstractImageMagickHandler implements ResizeHandler
{
    protected $standards = null;

    /**
     * @return null
     */
    protected function getStandards()
    {
        if ($this->standards === null) {
            $standardsConfig = Di::getInstance()->getConfig()->get('images')->get('sizes');
            foreach ($standardsConfig as $name => $size) {
                /** @var Config $size */
                $standardsConfig[$name] = [
                    'width' => $size->getValue('width'),
                    'height' => $size->getValue('height'),
                ];
            }
        }
        return $this->standards;
    }

    public function resize(Image $image, $width, $height)
    {
        // TODO: Implement resize() method.
    }

    public function resizeToStandard(Image $image, $standardName)
    {
        $standards = $this->getStandards();
        if (!isset($standards[$standardName])) {
            return $image;
        }

        list($width, $height) = $standards[$standardName];

        return $this->resize($image, $width, $height);
    }
}