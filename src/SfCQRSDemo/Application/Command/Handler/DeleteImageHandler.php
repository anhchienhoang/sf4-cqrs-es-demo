<?php

namespace SfCQRSDemo\Application\Command\Handler;

use SfCQRSDemo\Application\Command\DeleteImageCommand;
use SfCQRSDemo\Application\Service\ImageService;
use SfCQRSDemo\Model\Product\Product;
use SfCQRSDemo\Model\Product\ProductId;
use SfCQRSDemo\Model\Product\ProductRepository;

class DeleteImageHandler extends AbstractCommandHandler
{
    /**
     * @var ImageService
     */
    private $imageService;

    /**
     * @param ProductRepository $productRepository
     * @param ImageService      $imageService
     */
    public function __construct(ProductRepository $productRepository, ImageService $imageService)
    {
        $this->imageService = $imageService;

        parent::__construct($productRepository);
    }

    public function __invoke(DeleteImageCommand $command)
    {
        /** @var Product $product */
        $product = $this->productRepository->get(ProductId::fromString($command->getProductId()));

        $image = $product->getImages()[$command->getImageId()];

        $product->removeImage($image);

        $this->productRepository->add($product);

        $this->imageService->delete($image);
    }
}
