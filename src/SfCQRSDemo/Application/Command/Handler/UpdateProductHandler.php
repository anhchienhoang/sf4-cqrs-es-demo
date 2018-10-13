<?php

namespace SfCQRSDemo\Application\Command\Handler;

use SfCQRSDemo\Application\Command\UpdateProductCommand;
use SfCQRSDemo\Model\Product\Product;
use SfCQRSDemo\Model\Product\ProductId;

class UpdateProductHandler extends AbstractCommandHandler
{
    public function __invoke(UpdateProductCommand $command)
    {
        /** @var Product $product */
        $product = $this->productRepository->get(ProductId::fromString($command->getId()));

        $product->changeName($command->getName());
        $product->changePrice($command->getPrice());
        $product->changeDescription($command->getDescription());

        $this->productRepository->add($product);
    }
}
