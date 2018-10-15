<?php

namespace SfCQRSDemo\Infrastructure\Persistence;

use SfCQRSDemo\Model\Product\ProductView;

class ProductViewMapper
{
    /**
     * @var string
     */
    private $publicDir;

    /**
     * @var string
     */
    private $imageDir;

    /**
     * @var string
     */
    private $noImage;

    /**
     * @param string $publicDir
     * @param string $imageDir
     * @param string $noImage
     */
    public function __construct(string $publicDir, string $imageDir, string $noImage)
    {
        $this->publicDir = $publicDir;
        $this->imageDir = $imageDir;
        $this->noImage = $noImage;
    }

    public function map(array $data): ProductView
    {
        $images = null !== $data['images'] ? json_decode($data['images']) : [];

        $defaultImage = $this->noImage;

        if (count($images) > 0) {
            array_walk($images, function ($item) {
                $thumbnail = 'thumb_'.$item->image;

                if (file_exists($this->publicDir.'/'.$this->imageDir.'/'.$thumbnail)) {
                    $image = $thumbnail;
                } else {
                    $image = $item->image;
                }

                $item->image = $this->imageDir.'/'.$image;
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
