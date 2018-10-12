<?php

namespace SfCQRSDemo\Model\Product;

use SfCQRSDemo\Shared\Projection;

interface ProductProjection extends Projection
{
    public function projectWhenProductWasCreated(ProductWasCreated $event);

    public function projectWhenProductNameWasChanged(ProductNameWasChanged $event);

    public function projectWhenProductPriceWasChanged(ProductPriceWasChanged $event);

    public function projectWhenProductDescriptionWasChanged(ProductDescriptionWasChanged $event);

    public function projectWhenImageWasAdded(ImageWasAdded $event);
}
