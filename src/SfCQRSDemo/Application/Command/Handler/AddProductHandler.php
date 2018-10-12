<?php

namespace SfCQRSDemo\Application\Command\Handler;

use SfCQRSDemo\Application\Command\AddProductCommand;
use SfCQRSDemo\Model\Product\Product;
use SfCQRSDemo\Model\Product\ProductId;

class AddProductHandler extends AbstractCommandHandler
{
    public function __invoke(AddProductCommand $command)
    {
        $newProduct = Product::create(
            ProductId::generate(),
            $command->getName(),
            $command->getPrice(),
            $command->getDescription()
        );

        $this->productRepository->add($newProduct);
    }
}
