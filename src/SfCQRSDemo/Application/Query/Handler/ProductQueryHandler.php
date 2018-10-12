<?php

namespace SfCQRSDemo\Application\Query\Handler;

use SfCQRSDemo\Application\Query\ProductQuery;

class ProductQueryHandler extends AbstractQueryHandler
{
    public function __invoke(ProductQuery $query)
    {
        return $this->repository->get($query->getId());
    }
}
