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

        if ($product->getName() !== $command->getName()) {
            $product->changeName($command->getName());
        }

        if ($product->getPrice() !== $command->getPrice()) {
            $product->changePrice($command->getPrice());
        }

        if ($product->getDescription() !== $command->getDescription()) {
            $product->changeDescription($command->getDescription());
        }

        $this->productRepository->add($product);
    }
}
