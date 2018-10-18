<?php

namespace SfCQRSDemo\Model\Product;

use SfCQRSDemo\Shared\AggregateId;
use SfCQRSDemo\Shared\UuidGenerator;

class ProductId implements AggregateId
{
    private $productId;

    public static function generate(): ProductId
    {
        return new ProductId(UuidGenerator::generate());
    }

    public static function fromString(string $productId): ProductId
    {
        return new ProductId($productId);
    }

    public function __toString()
    {
        return (string) $this->productId;
    }

    public function equals(AggregateId $other)
    {
        return $other instanceof ProductId && $other->productId === $this->productId;
    }

    public function __construct(string $productId)
    {
        $this->productId = $productId;
    }
}
