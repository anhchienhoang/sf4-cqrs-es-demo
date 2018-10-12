<?php

namespace SfCQRSDemo\Application\Query\Handler;

use SfCQRSDemo\Model\Product\ProductQueryRepository;

abstract class AbstractQueryHandler
{
    protected $repository;

    public function __construct(ProductQueryRepository $repository)
    {
        $this->repository = $repository;
    }
}
