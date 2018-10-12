<?php

namespace SfCQRSDemo\Application\Query\Handler;

use SfCQRSDemo\Application\Query\ProductsCountQuery;

class ProductsCountHandler extends AbstractQueryHandler
{
    public function __invoke(ProductsCountQuery $query)
    {
        return $this->repository->count();
    }
}
