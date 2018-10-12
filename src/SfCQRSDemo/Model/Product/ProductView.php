<?php

namespace SfCQRSDemo\Model\Product;

class ProductView
{
    const DEFAULT_IMAGE = 'no_image.jpg';

    /**
     * @var string
     */
    private $id;

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
     * @var array
     */
    private $images;

    /**
     * @var string
     */
    private $defaultImage;

    /**
     * @param string $id
     * @param string $name
     * @param float  $price
     * @param string $description
     * @param array  $images
     * @param string $defaultImage
     */
    public function __construct(string $id, string $name, float $price, string $description, array $images, string $defaultImage)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->images = $images;
        $this->defaultImage = $defaultImage;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return string
     */
    public function getDefaultImage(): string
    {
        return $this->defaultImage;
    }
}
