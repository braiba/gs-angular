<?php

namespace Geekstitch\ImageProcessors\Handlers;

use Geekstitch\Entity\Image;

interface ResizeHandler
{
    public function resize(Image $image, $width, $height);

    public function resizeToStandard(Image $image, $standardName);
}
