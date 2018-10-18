<?php

namespace SfCQRSDemo\Tests\Unit\Infrastructure\Persistence;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SfCQRSDemo\Infrastructure\Persistence\ProductViewMapper;
use SfCQRSDemo\Model\Product\ProductView;
use Symfony\Component\Filesystem\Filesystem;

class ProductViewMapperTest extends TestCase
{
    /**
     * @var MockObject|Filesystem
     */
    private $filesystem;

    public function setUp()
    {
        $this->filesystem = $this->getMockBuilder(Filesystem::class)->getMock();
    }

    /**
     * @test
     */
    public function itShouldReturnProductView()
    {
        $this->filesystem->expects($this->at(0))
            ->method('exists')
            ->willReturn(true);

        $this->filesystem->expects($this->at(1))
            ->method('exists')
            ->willReturn(false);

        $mapper = new ProductViewMapper('public', 'images', 'no_image.png', $this->filesystem);

        $data = [
            'id' => 1,
            'name' => 'item 1',
            'price' => 100,
            'description' => 'desc 1',
            'images' => '[{"id":1,"image":"image_1.jpg"},{"id":2,"image":"image_2.jpg"}]',
        ];

        $productView = $mapper->map($data);

        $this->assertInstanceOf(ProductView::class, $productView);
        $this->assertEquals(1, $productView->getId());
        $this->assertEquals('item 1', $productView->getName());
        $this->assertEquals('desc 1', $productView->getDescription());

        $this->assertEquals(1, $productView->getImages()[0]->id);
        $this->assertEquals('images/thumb_image_1.jpg', $productView->getImages()[0]->image);

        $this->assertEquals(2, $productView->getImages()[1]->id);
        $this->assertEquals('images/image_2.jpg', $productView->getImages()[1]->image);
    }
}
