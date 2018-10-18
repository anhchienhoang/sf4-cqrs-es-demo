<?php

namespace SfCQRSDemo\Application\Command;

class DeleteImageCommand
{
    /**
     * @var string
     */
    private $productId;

    /**
     * @var string
     */
    private $imageId;

    /**
     * @param string  $imageId
     * @param string $productId
     */
    public function __construct(string $imageId, string $productId)
    {
        $this->productId = $productId;
        $this->imageId = $imageId;
    }

    /**
     * @return string
     */
    public function getImageId(): string
    {
        return $this->imageId;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }
}
