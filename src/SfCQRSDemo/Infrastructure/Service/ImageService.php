<?php

namespace SfCQRSDemo\Infrastructure\Service;

use Gumlet\ImageResize;
use Psr\Log\LoggerInterface;
use SfCQRSDemo\Application\Service\ImageService as ImageServicePort;
use SfCQRSDemo\Model\Product\Image;
use Symfony\Component\Filesystem\Filesystem;

class ImageService implements ImageServicePort
{
    private $cropSize;

    private $imageDir;

    private $logger;

    private $filesystem;

    public function __construct(int $cropSize, string $imageDir, LoggerInterface $logger, Filesystem $filesystem)
    {
        $this->cropSize = $cropSize;
        $this->imageDir = $imageDir;
        $this->logger = $logger;
        $this->filesystem = $filesystem;
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

    public function delete(Image $image)
    {
        try {
            $this->filesystem->remove($this->imageDir.'/'.$image->getName());
            $this->filesystem->remove($this->imageDir.'/thumb_'.$image->getName());
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
