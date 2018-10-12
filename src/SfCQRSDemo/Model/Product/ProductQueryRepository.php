<?php

namespace SfCQRSDemo\Model\Product;

interface ProductQueryRepository
{
    public function get(string $id): ProductView;

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return ProductView[]
     */
    public function fetchAll(int $page, int $perPage): array;

    public function count(): int;
}
