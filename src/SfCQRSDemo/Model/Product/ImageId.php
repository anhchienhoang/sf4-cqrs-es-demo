<?php

namespace SfCQRSDemo\Model\Product;

use SfCQRSDemo\Shared\UuidGenerator;

class ImageId
{
    /**
     * @var string
     */
    private $imageId;

    public static function fromString(string $imageId)
    {
        return new ImageId($imageId);
    }

    public function __toString(): string
    {
        return (string) $this->imageId;
    }

    public function equals(ImageId $other): bool
    {
        return $this->imageId === $other->imageId;
    }

    public static function generate(): ImageId
    {
        return new ImageId(UuidGenerator::generate());
    }

    /**
     * @param string $imageId
     */
    private function __construct(string $imageId)
    {
        $this->imageId = $imageId;
    }
}
