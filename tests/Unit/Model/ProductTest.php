<?php

namespace SfCQRSDemo\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use SfCQRSDemo\Model\Product\ImageWasAdded;
use SfCQRSDemo\Model\Product\Product;
use SfCQRSDemo\Model\Product\ProductDescriptionWasChanged;
use SfCQRSDemo\Model\Product\ProductId;
use SfCQRSDemo\Model\Product\ProductNameWasChanged;
use SfCQRSDemo\Model\Product\ProductPriceWasChanged;
use SfCQRSDemo\Model\Product\ProductWasCreated;
use SfCQRSDemo\Shared\DomainEvents;
use SfCQRSDemo\Shared\DomainEventsHistory;

class ProductTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldRecordProductWasCreatedEvent()
    {
        $product = Product::create(ProductId::generate(), 'test', 100, 'test');

        $this->assertTrue(
            $this->assertEvent($product->getRecordedEvents(), ProductWasCreated::class)
        );

        $this->assertEquals('test', $product->getName());
        $this->assertEquals(100, $product->getPrice());
        $this->assertEquals('test', $product->getDescription());
    }

    /**
     * @test
     */
    public function itShouldRecordProductNameWasChangedEvent()
    {
        $product = Product::create(ProductId::generate(), 'test', 100, 'test');

        $product->changeName('test1');

        $this->assertTrue(
            $this->assertEvent($product->getRecordedEvents(), ProductNameWasChanged::class)
        );

        $this->assertEquals('test1', $product->getName());
    }

    /**
     * @test
     */
    public function itShouldDoNothingWhenNewNameIsTheSame()
    {
        $product = Product::create(ProductId::generate(), 'test', 100, 'test');

        $product->changeName('test');

        $this->assertFalse(
            $this->assertEvent($product->getRecordedEvents(), ProductNameWasChanged::class)
        );

        $this->assertEquals('test', $product->getName());
    }

    /**
     * @test
     */
    public function itShouldRecordProductPriceWasChangedEvent()
    {
        $product = Product::create(ProductId::generate(), 'test', 100, 'test');

        $product->changePrice(101);

        $this->assertTrue(
            $this->assertEvent($product->getRecordedEvents(), ProductPriceWasChanged::class)
        );

        $this->assertEquals(101, $product->getPrice());
    }

    /**
     * @test
     */
    public function itShouldDoNothingWhenNewPriceIsTheSame()
    {
        $product = Product::create(ProductId::generate(), 'test', 100, 'test');

        $product->changePrice(100);

        $this->assertFalse(
            $this->assertEvent($product->getRecordedEvents(), ProductPriceWasChanged::class)
        );

        $this->assertEquals(100, $product->getPrice());
    }

    /**
     * @test
     */
    public function itShouldRecordProductDescriptionWasChangedEvent()
    {
        $product = Product::create(ProductId::generate(), 'test', 100, 'description');

        $product->changeDescription('new description');

        $this->assertTrue(
            $this->assertEvent($product->getRecordedEvents(), ProductDescriptionWasChanged::class)
        );

        $this->assertEquals('new description', $product->getDescription());
    }

    /**
     * @test
     */
    public function itShouldDoNothingWhenNewDescriptionIsTheSame()
    {
        $product = Product::create(ProductId::generate(), 'test', 100, 'description');

        $product->changeDescription('description');

        $this->assertFalse(
            $this->assertEvent($product->getRecordedEvents(), ProductDescriptionWasChanged::class)
        );

        $this->assertEquals('description', $product->getDescription());
    }

    /**
     * @test
     */
    public function itShouldRecordImageWasAddedEvent()
    {
        $product = Product::create(ProductId::generate(), 'test', 100, 'description');

        $product->addImage('an_image.jpg');

        $this->assertTrue(
            $this->assertEvent($product->getRecordedEvents(), ImageWasAdded::class)
        );
    }

    /**
     * @test
     */
    public function itShouldBeReconstitutedFromHistory()
    {
        $productId = ProductId::generate();

        $eventsHistory = new DomainEventsHistory(
            $productId,
            [
                new ProductWasCreated($productId, 'test', 100, 'desc'),
                new ProductNameWasChanged($productId, 'test1'),
                new ProductPriceWasChanged($productId, 120),
                new ProductNameWasChanged($productId, 'test2'),
            ]
        );

        $product = Product::reconstituteFromHistory($eventsHistory);

        $this->assertEquals('test2', $product->getName());
        $this->assertEquals(120, $product->getPrice());
        $this->assertEquals('desc', $product->getDescription());
    }

    private function assertEvent(DomainEvents $recodedEvents, $eventClass): bool
    {
        foreach ($recodedEvents as $event) {
            if (get_class($event) === $eventClass) {
                return true;
            }
        }

        return false;
    }
}
