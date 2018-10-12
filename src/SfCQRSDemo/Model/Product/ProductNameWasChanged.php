<?php

namespace SfCQRSDemo\Model\Product;

use SfCQRSDemo\Shared\AggregateId;
use SfCQRSDemo\Shared\DomainEvent;

class ProductNameWasChanged implements DomainEvent
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var string
     */
    private $name;

    /**
     * @param ProductId $productId
     * @param string    $name
     */
    public function __construct(ProductId $productId, string $name)
    {
        $this->productId = $productId;
        $this->name = $name;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
