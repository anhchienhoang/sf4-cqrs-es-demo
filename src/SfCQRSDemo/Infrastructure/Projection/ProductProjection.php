<?php

namespace SfCQRSDemo\Infrastructure\Projection;

use Doctrine\DBAL\Connection;
use SfCQRSDemo\Model\Product\ImageWasAdded;
use SfCQRSDemo\Model\Product\ProductDescriptionWasChanged;
use SfCQRSDemo\Model\Product\ProductNameWasChanged;
use SfCQRSDemo\Model\Product\ProductPriceWasChanged;
use SfCQRSDemo\Model\Product\ProductProjection as ProductProjectionPort;
use SfCQRSDemo\Model\Product\ProductWasCreated;
use SfCQRSDemo\Shared\AbstractProjection;

class ProductProjection extends AbstractProjection implements ProductProjectionPort
{
    protected $connection;

    /**
     * @param $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function projectWhenProductWasCreated(ProductWasCreated $event)
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO `products` (`id`, `name`, `price`, `description`) 
             VALUES (:id, :name, :price, :description)'
        );

        $stmt->execute([
            ':id' => (string) $event->getAggregateId(),
            ':name' => $event->getName(),
            ':price' => $event->getPrice(),
            ':description' => $event->getDescription(),
        ]);
    }

    public function projectWhenProductNameWasChanged(ProductNameWasChanged $event)
    {
        $this->connection->executeQuery(
            'UPDATE `products` SET `name`=? WHERE id=?',
            [
                $event->getName(),
                (string) $event->getAggregateId(),
            ]
        );
    }

    public function projectWhenProductPriceWasChanged(ProductPriceWasChanged $event)
    {
        $this->connection->executeQuery(
            'UPDATE `products` SET `price`=? WHERE id=?',
            [
                $event->getPrice(),
                (string) $event->getAggregateId(),
            ]
        );
    }

    public function projectWhenProductDescriptionWasChanged(ProductDescriptionWasChanged $event)
    {
        $this->connection->executeQuery(
            'UPDATE `products` SET `description`=? WHERE id=?',
            [
                $event->getDescription(),
                (string) $event->getAggregateId(),
            ]
        );
    }

    public function projectWhenImageWasAdded(ImageWasAdded $event)
    {
        $this->connection->beginTransaction();

        $stmt = $this->connection->prepare(
            'INSERT INTO `product_images` (`id`, `product_id`, `image`)
            VALUES (:id, :productId, :image)'
        );

        $stmt->execute([
            ':id' => (string) $event->getImageId(),
            ':productId' => (string) $event->getAggregateId(),
            ':image' => $event->getImage(),
        ]);

        $images = $this->connection->fetchColumn(
            'SELECT `images` FROM `products` WHERE id=?',
            [(string) $event->getAggregateId()]
        );

        if (null !== $images) {
            $images = json_decode($images);
        }

        $images[] = ['id' => (string) $event->getImageId(), 'image' => $event->getImage()];

        $this->connection->executeQuery(
            'UPDATE `products` SET `images`=? WHERE id=?',
            [
                json_encode($images),
                (string) $event->getAggregateId(),
            ]
        );

        $this->connection->commit();
    }
}
