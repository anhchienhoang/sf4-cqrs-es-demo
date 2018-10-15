<?php

namespace SfCQRSDemo\Infrastructure\Service;

use Gumlet\ImageResize;
use Psr\Log\LoggerInterface;
use SfCQRSDemo\Application\Service\ImageService as ImageServicePort;

class ImageService implements ImageServicePort
{
    private $cropSize;

    private $imageDir;

    private $logger;

    public function __construct(int $cropSize, string $imageDir, LoggerInterface $logger)
    {
        $this->cropSize = $cropSize;
        $this->imageDir = $imageDir;
        $this->logger = $logger;
    }

    public function resize(string $image)
    {
        try {
            $imageResize = new ImageResize($this->imageDir.'/'.$image);
            $imageResize->crop($this->cropSize, $this->cropSize, true, ImageResize::CROPCENTER);
            $imageResize->save($this->imageDir.'/thumb_'.$image);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
