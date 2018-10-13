<?php

namespace SfCQRSDemo\Model\Product;

use SfCQRSDemo\Shared\AggregateId;
use SfCQRSDemo\Shared\AggregateRoot;
use SfCQRSDemo\Shared\DomainEventsHistory;

class Product extends AggregateRoot
{
    /**
     * @var ProductId
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
     * @var Image[]
     */
    private $images;

    /**
     * @param string  $id
     * @param string  $name
     * @param float   $price
     * @param string  $description
     * @param Image[] $images
     */
    private function __construct($id, $name, $price, $description, $images = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->images = $images;
    }

    private static function createEmptyProductWithId(AggregateId $productId)
    {
        return new Product($productId, '', 0, '');
    }

    public static function create(ProductId $id, string $name, float $price, string $description)
    {
        $newProduct = new Product($id, $name, $price, $description);
        $newProduct->recordThat(new ProductWasCreated($id, $name, $price, $description));

        return $newProduct;
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
     * @return Image[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    public function changeName(string $newName)
    {
        if ($newName === $this->name) {
            return;
        }

        $this->applyAndRecordThat(
            new ProductNameWasChanged($this->id, $newName)
        );
    }

    public function changePrice(float $newPrice)
    {
        if ($newPrice === $this->price) {
            return;

        }
        $this->applyAndRecordThat(
            new ProductPriceWasChanged($this->id, $newPrice)
        );
    }

    public function changeDescription(string $newDescription)
    {
        if ($newDescription === $this->description) {
            return;
        }

        $this->applyAndRecordThat(
            new ProductDescriptionWasChanged($this->id, $newDescription)
        );
    }

    public function addImage(string $image)
    {
        $this->applyAndRecordThat(new ImageWasAdded($this->id, ImageId::generate(), $image));
    }

    public static function reconstituteFromHistory(DomainEventsHistory $eventsHistory)
    {
        $product = static::createEmptyProductWithId($eventsHistory->getAggregateId());

        foreach ($eventsHistory as $event) {
            $product->apply($event);
        }

        return $product;
    }

    protected function applyProductWasCreated(ProductWasCreated $event)
    {
        $this->name = $event->getName();
        $this->price = $event->getPrice();
        $this->description = $event->getDescription();
    }

    protected function applyProductNameWasChanged(ProductNameWasChanged $event)
    {
        $this->name = $event->getName();
    }

    protected function applyProductPriceWasChanged(ProductPriceWasChanged $event)
    {
        $this->price = $event->getPrice();
    }

    protected function applyProductDescriptionWasChanged(ProductDescriptionWasChanged $event)
    {
        $this->description = $event->getDescription();
    }

    protected function applyImageWasAdded(ImageWasAdded $event)
    {
        $this->images[] = Image::create($event->getImageId(), $event->getImage());
    }
}
