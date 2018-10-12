<?php

namespace SfCQRSDemo\Model\Product;

use SfCQRSDemo\Shared\AggregateId;
use SfCQRSDemo\Shared\DomainEvent;

class ImageWasAdded implements DomainEvent
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var ImageId
     */
    private $imageId;

    /**
     * @var string
     */
    private $image;

    /**
     * @param ProductId $productId
     * @param ImageId   $imageId
     * @param string     $image
     */
    public function __construct(ProductId $productId, ImageId $imageId, string $image)
    {
        $this->productId = $productId;
        $this->imageId = $imageId;
        $this->image = $image;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->productId;
    }

    /**
     * @return ImageId
     */
    public function getImageId(): ImageId
    {
        return $this->imageId;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }
}
