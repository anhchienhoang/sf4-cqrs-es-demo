<?php

namespace SfCQRSDemo\Infrastructure\Controller;

use SfCQRSDemo\Application\Query\PagingProductsQuery;
use SfCQRSDemo\Application\Query\ProductsCountQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    const PRODUCT_PER_PAGE = 12;

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
        $query = new PagingProductsQuery($page, static::PRODUCT_PER_PAGE);

        $productsCount = $bus->dispatch(new ProductsCountQuery());

        $maxPages = floor($productsCount / static::PRODUCT_PER_PAGE);

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
