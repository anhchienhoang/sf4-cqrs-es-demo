<?php

namespace SfCQRSDemo\Application\Command\Handler;

use SfCQRSDemo\Model\Product\ProductRepository;

abstract class AbstractCommandHandler
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
}
