<?php

namespace SfCQRSDemo\Model\Product;

use SfCQRSDemo\Shared\AggregateId;
use SfCQRSDemo\Shared\DomainEvent;

class ImageWasRemoved implements DomainEvent
{
    /**
     * @var Image
     */
    private $image;

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @param Image     $image
     * @param ProductId $productId
     */
    public function __construct(Image $image, ProductId $productId)
    {
        $this->image = $image;
        $this->productId = $productId;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->productId;
    }

    /**
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }
}
