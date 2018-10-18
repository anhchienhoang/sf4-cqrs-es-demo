<?php

namespace SfCQRSDemo\Infrastructure\Persistence;

use SfCQRSDemo\Model\Product\ProductView;
use Symfony\Component\Filesystem\Filesystem;

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
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param string     $publicDir
     * @param string     $imageDir
     * @param string     $noImage
     * @param Filesystem $filesystem
     */
    public function __construct(string $publicDir, string $imageDir, string $noImage, Filesystem $filesystem)
    {
        $this->publicDir = $publicDir;
        $this->imageDir = $imageDir;
        $this->noImage = $noImage;
        $this->filesystem = $filesystem;
    }

    public function map(array $data): ProductView
    {
        $images = null !== $data['images'] ? json_decode($data['images']) : [];

        $defaultImage = $this->noImage;

        if (count($images) > 0) {
            array_walk($images, function ($item) {
                $thumbnail = 'thumb_'.$item->image;

                if ($this->filesystem->exists($this->publicDir.'/'.$this->imageDir.'/'.$thumbnail)) {
                    $image = $thumbnail;
                } else {
                    $image = $item->image;
                }

                $item->origin = $this->imageDir.'/'.$item->image;
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
