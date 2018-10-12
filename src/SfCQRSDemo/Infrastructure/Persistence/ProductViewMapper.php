<?php

namespace SfCQRSDemo\Infrastructure\Persistence;

use SfCQRSDemo\Model\Product\ProductView;

class ProductViewMapper
{
    /**
     * @var string
     */
    private $imageDir;

    /**
     * @var string
     */
    private $noImage;

    public function __construct(string $imageDir, string $noImage)
    {
        $this->imageDir = $imageDir;
        $this->noImage = $noImage;
    }

    public function map(array $data): ProductView
    {
        $images = null !== $data['images'] ? json_decode($data['images']) : [];

        $defaultImage = $this->noImage;

        if (count($images) > 0) {
            array_walk($images, function ($item) {
                $item->image = $this->imageDir.'/'.$item->image;
            });

            $firstImage = $images[0];
            $defaultImage = $firstImage->image;
        }

        return new ProductView(
            $data['id'],
            $data['name'],
            $data['price'],
            $data['description'],
            $images,
            $defaultImage
        );
    }
}
