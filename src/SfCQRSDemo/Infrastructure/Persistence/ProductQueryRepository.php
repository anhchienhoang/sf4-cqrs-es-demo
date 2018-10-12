<?php

namespace SfCQRSDemo\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use SfCQRSDemo\Model\Product\ProductQueryRepository as ProductQueryPort;
use SfCQRSDemo\Model\Product\ProductView;

class ProductQueryRepository implements ProductQueryPort
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function get(string $id): ProductView
    {
        $stmt = $this->connection->prepare('SELECT * FROM `products` WHERE id=:id');
        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return new ProductView(
            $data['id'],
            $data['name'],
            $data['price'],
            $data['description']
        );
    }

    public function fetchAll(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->connection->prepare('SELECT * FROM `products` LIMIT :offset,:perPage');
        $stmt->bindValue(':perPage', $perPage, ParameterType::INTEGER);
        $stmt->bindValue(':offset', $offset, ParameterType::INTEGER);
        $stmt->execute();

        $products = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $products[] = new ProductView(
                $row['id'],
                $row['name'],
                (float) $row['price'],
                $row['description']
            );
        }

        $stmt->closeCursor();

        return $products;
    }

    /**
     * TODO: implement caching
     *
     * @return int
     */
    public function count(): int
    {
        return (int) $this->connection->fetchColumn('SELECT COUNT(id) AS total from `products`');
    }
}
