<?php

namespace SfCQRSDemo\Application\Service;

use SfCQRSDemo\Model\Product\Image;

interface ImageService
{
    public function resize(string $image);

    public function delete(Image $image);
}
