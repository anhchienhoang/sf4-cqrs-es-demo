<?php

namespace SfCQRSDemo\Application\Command;

use SfCQRSDemo\Model\Product\ProductId;

class AddProductCommand
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
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $description;

    /**
     * @param ProductId $productId
     * @param string    $name
     * @param float     $price
     * @param string    $description
     */
    public function __construct(ProductId $productId, string $name, float $price, string $description)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
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
