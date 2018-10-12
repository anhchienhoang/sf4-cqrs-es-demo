<?php

namespace SfCQRSDemo\Model\Product;

use SfCQRSDemo\Shared\AggregateId;
use SfCQRSDemo\Shared\DomainEvent;

class ProductPriceWasChanged implements DomainEvent
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var float
     */
    private $price;

    /**
     * @param ProductId $productId
     * @param float     $price
     */
    public function __construct(ProductId $productId, float $price)
    {
        $this->productId = $productId;
        $this->price = $price;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->productId;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }
}
