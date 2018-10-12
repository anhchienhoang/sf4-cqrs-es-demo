<?php

namespace SfCQRSDemo\Application\Command\Handler;

use SfCQRSDemo\Application\Command\AddImageCommand;
use SfCQRSDemo\Model\Product\Product;
use SfCQRSDemo\Model\Product\ProductId;

class AddImageHandler extends AbstractCommandHandler
{
    public function __invoke(AddImageCommand $command)
    {
        /** @var Product $product */
        $product = $this->productRepository->get(ProductId::fromString($command->getProductId()));
        $product->addImage($command->getImage());

        $this->productRepository->add($product);
    }
}
