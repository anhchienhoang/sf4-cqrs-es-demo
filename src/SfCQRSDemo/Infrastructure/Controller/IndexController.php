<?php

namespace SfCQRSDemo\Infrastructure\Controller;

use SfCQRSDemo\Application\Query\PagingProductsQuery;
use SfCQRSDemo\Application\Query\ProductsCountQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * @Route("/{page}", name="index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     *
     * @param int                 $page
     * @param MessageBusInterface $bus
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(int $page, MessageBusInterface $bus)
    {
        $productsPerPage = $this->getParameter('products_per_page');

        $query = new PagingProductsQuery($page, $productsPerPage);

        $productsCount = $bus->dispatch(new ProductsCountQuery());

        $maxPages = ceil($productsCount / $productsPerPage);

        $products = $bus->dispatch($query);

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
