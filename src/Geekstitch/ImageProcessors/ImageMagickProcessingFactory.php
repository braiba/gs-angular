<?php

namespace Geekstitch\ImageProcessors;

use Geekstitch\Core\Di;
use Geekstitch\Entity\Image;

/**
 *
 * @author Thomas
 */
abstract class ImageMagickProcessingFactory extends ImageProcessingFactory
{
    protected $imageMagickDir = null;

    /**
     *
     * @return string
     */
    public function getImageMagickDir()
    {
        if ($this->imageMagickDir === null) {
            $config = Di::getInstance()->getConfig();
            $this->imageMagickDir = $config->get('images')->get('imageMagick')->getValue('dir', false);
        }

        return $this->imageMagickDir;
    }

    /**
     *
     * @param string $inputFilename
     *
     * @return string
     */
    protected abstract function getCommand($inputFilename);

    /**
     *
     * @param Image $source
     *
     * @return string
     */
    protected abstract function generateOutputFilename(Image $source);

    /**
     * @inheritDoc
     */
    public function generateProcessedImage(Image $source)
    {
        $imagesDir = $this->getImagesDir();
        $inputFilename = $imagesDir . $source->getPath();
        $outputFilename = $this->generateOutputFilename($source);

        $command = $this->getCommand($inputFilename);
        $output = array(
            array('pipe', 'r'), // stdin
            array('pipe', 'w'), // stdout
            array('pipe', 'w')  // stderr
        );
        $pipes = array();
        $process = proc_open($command, $output, $pipes, $this->getImageMagickDir());
        if (!is_resource($process)) {
            throw new \RuntimeException('Failed to execute ImageMagick command:' . $command);
        }

        // Close input pipe (allows process to start to run)
        fclose($pipes[0]);

        $stdOut = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $stdErr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        // Close Process
        proc_close($process);

        if (!empty($stdErr)){
            throw new \RuntimeException('ImageMagick reported an error:' . $stdErr);
        }

        $imageResource = imagecreatefromstring($stdOut);

        imagepng($imageResource, $this->getImagesDir() . $outputFilename);

        return $outputFilename;
    }
}
