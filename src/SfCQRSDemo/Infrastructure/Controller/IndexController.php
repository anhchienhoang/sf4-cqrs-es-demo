<?php

namespace SfCQRSDemo\Infrastructure\Controller;

use SfCQRSDemo\Application\Query\PagingProductsQuery;
use SfCQRSDemo\Application\Query\ProductsCountQuery;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends BaseController
{
    /**
     * @Route("/{page}", name="index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     *
     * @param int                 $page
     * @param MessageBusInterface $bus
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(int $page)
    {
        $productsPerPage = $this->getParameter('products_per_page');

        $query = new PagingProductsQuery($page, $productsPerPage);

        $productsCount = $this->handleMessage(new ProductsCountQuery());

        $maxPages = ceil($productsCount / $productsPerPage);

        $products = $this->handleMessage($query);

        return $this->render(
            'index.html.twig',
            [
                'products' => $products,
                'maxPages' => $maxPages,
                'thisPage' => $page,
            ]
        );
    }
}
