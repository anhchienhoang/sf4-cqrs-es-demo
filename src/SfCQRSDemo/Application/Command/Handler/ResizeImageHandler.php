<?php

namespace SfCQRSDemo\Application\Command\Handler;

use SfCQRSDemo\Application\Command\ResizeImageCommand;
use SfCQRSDemo\Application\Service\ImageService;

class ResizeImageHandler
{
    private $imageService;

    /**
     * @param ImageService $imageService
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function __invoke(ResizeImageCommand $command)
    {
        $this->imageService->resize($command->getImage());
    }
}
