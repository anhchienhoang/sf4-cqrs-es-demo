<?php

namespace SfCQRSDemo\Model\Product;

class Image
{
    /**
     * @var ImageId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @param ImageId $id
     * @param string  $name
     */
    public function __construct(ImageId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function create(ImageId $imageId, string $name): Image
    {
        return new Image($imageId, $name);
    }
}
