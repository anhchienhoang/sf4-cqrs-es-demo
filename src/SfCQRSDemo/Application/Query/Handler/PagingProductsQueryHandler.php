<?php
namespace SfCQRSDemo\Application\Query\Handler;

use SfCQRSDemo\Application\Query\PagingProductsQuery;

class PagingProductsQueryHandler extends AbstractQueryHandler
{
    public function __invoke(PagingProductsQuery $query)
    {
        return $this->repository->fetchAll($query->getPage(), $query->getPerPage());
    }
}
