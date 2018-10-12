<?php

namespace SfCQRSDemo\Model\Product;

use SfCQRSDemo\Shared\AggregateId;
use SfCQRSDemo\Shared\DomainEvent;

class ProductDescriptionWasChanged implements DomainEvent
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var string
     */
    private $description;

    /**
     * @param ProductId $productId
     * @param string    $description
     */
    public function __construct(ProductId $productId, string $description)
    {
        $this->productId = $productId;
        $this->description = $description;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
