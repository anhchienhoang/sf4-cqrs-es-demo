<?php

namespace SfCQRSDemo\Model\Product;

use Ramsey\Uuid\Uuid;

class ImageId
{
    /**
     * @var string
     */
    private $imageId;

    /**
     * @param string $imageId
     */
    public function __construct(string $imageId)
    {
        $this->imageId = $imageId;
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
        return new ImageId(Uuid::uuid4()->toString());
    }
}
