<?php

namespace SfCQRSDemo\Model\Product;

use SfCQRSDemo\Shared\AggregateId;
use SfCQRSDemo\Shared\DomainEvent;

class ProductWasCreated implements DomainEvent
{
    /**
     * @var ProductId
     */
    private $productId;

    private $name;
    private $price;
    private $description;

    public function __construct($productId, $name, $price, $description)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
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

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
