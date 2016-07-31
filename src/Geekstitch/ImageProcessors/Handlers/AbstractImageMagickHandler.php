<?php

namespace Geekstitch\ImageProcessors\Handlers;

use Exception;

class AbstractImageMagickHandler
{

    /**
     * Execute an Image Magick command
     *
     * @param string $command
     *
     * @return resource
     *
     * @throws Exception
     */
    protected function doMagick($command) {
        $output = [
            ['pipe', 'r'], // stdin
            ['pipe', 'w'], // stdout
            ['pipe', 'w'],  // stderr
        ];
        $pipes = [];
        
        $process = proc_open($command, $output, $pipes, IMAGEMAGICK_PATH);
        if (is_resource($process))
        {
            // Close input pipe (allows process to start to run)
            fclose($pipes[0]);

            $stdOut = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $stdErr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            // Close Process
            proc_close($process);

            if (!empty($stdErr)){
                throw new Exception('ImageMagick returned the following error: ' . $stdErr);
            } else {
                return imagecreatefromstring($stdOut);
            }
        } else {
            throw new Exception('ImageMagick failed to execute the following command: ' . $command);
        }
    }
}