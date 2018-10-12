<?php

namespace SfCQRSDemo\Infrastructure\Controller;

use SfCQRSDemo\Application\Command\AddProductCommand;
use SfCQRSDemo\Application\Command\UpdateProductCommand;
use SfCQRSDemo\Application\Query\ProductQuery;
use SfCQRSDemo\Infrastructure\UI\Form\ProductFormType;
use SfCQRSDemo\Model\Product\ProductView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/detail/{id}", name="product_detail", requirements={"id" = ".+"})
     */
    public function detail(string $id, MessageBusInterface $bus)
    {
        $productQuery = new ProductQuery($id);

        $product = $bus->dispatch($productQuery);

        return $this->render(
            'product/detail.html.twig',
            ['product' => $product]
        );
    }

    /**
     * @Route("/update/{id}", name="product_update", requirements={"id" = ".+"})
     *
     * @param string              $id
     * @param Request             $request
     * @param MessageBusInterface $bus
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(string $id, Request $request, MessageBusInterface $bus)
    {
        $productQuery = new ProductQuery($id);

        /** @var ProductView $product */
        $product = $bus->dispatch($productQuery);

        $form = $this->createForm(
            ProductFormType::class,
            [
                ProductFormType::NAME => $product->getName(),
                ProductFormType::PRICE => $product->getPrice(),
                ProductFormType::DESCRIPTION => $product->getDescription(),
            ],
            ['submitLabel' => 'save_changes']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $updateCommand = new UpdateProductCommand(
                $product->getId(),
                $data[ProductFormType::NAME],
                $data[ProductFormType::PRICE],
                $data[ProductFormType::DESCRIPTION]
            );

            $bus->dispatch($updateCommand);

            return $this->redirectToRoute('product_detail', ['id' => $product->getId()]);
        }

        return $this->render(
            'product/update.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/add", name="product_add_new")
     *
     * @param Request             $request
     * @param MessageBusInterface $bus
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request, MessageBusInterface $bus)
    {
        $form = $this->createForm(ProductFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $productCommand = new AddProductCommand(
                $data[ProductFormType::NAME],
                $data[ProductFormType::PRICE],
                $data[ProductFormType::DESCRIPTION]
            );

            $bus->dispatch($productCommand);

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'product/add.html.twig',
            ['form' => $form->createView()]
        );
    }
}
