<?php

namespace SfCQRSDemo\Application\Command;

class AddImageCommand
{
    /**
     * @var string
     */
    private $image;

    /**
     * @var string
     */
    private $productId;

    /**
     * @param string $image
     * @param string $productId
     */
    public function __construct($image, $productId)
    {
        $this->image = $image;
        $this->productId = $productId;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }
}
