<?php

namespace SfCQRSDemo\Infrastructure\Controller;

use Psr\Log\LoggerInterface;
use SfCQRSDemo\Application\Command\AddImageCommand;
use SfCQRSDemo\Application\Command\AddProductCommand;
use SfCQRSDemo\Application\Command\DeleteImageCommand;
use SfCQRSDemo\Application\Command\ResizeImageCommand;
use SfCQRSDemo\Application\Command\UpdateProductCommand;
use SfCQRSDemo\Application\Query\ProductQuery;
use SfCQRSDemo\Infrastructure\UI\Form\ImageUploadType;
use SfCQRSDemo\Infrastructure\UI\Form\ProductFormType;
use SfCQRSDemo\Model\Product\ProductId;
use SfCQRSDemo\Model\Product\ProductView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

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

            $productId = ProductId::generate();

            $productCommand = new AddProductCommand(
                $productId,
                $data[ProductFormType::NAME],
                $data[ProductFormType::PRICE],
                $data[ProductFormType::DESCRIPTION]
            );

            $bus->dispatch($productCommand);

            return $this->redirectToRoute('product_add_image', ['id' => (string) $productId]);
        }

        return $this->render(
            'product/add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/add/image/{id}", name="product_add_image", requirements={"id" = ".+"})
     *
     * @param string              $id
     * @param Request             $request
     * @param MessageBusInterface $bus
     * @param TranslatorInterface $translator
     * @param LoggerInterface     $logger
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addImage(string $id, Request $request, MessageBusInterface $bus, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $productQuery = new ProductQuery($id);

        /** @var ProductView $product */
        $product = $bus->dispatch($productQuery);

        $form = $this->createForm(ImageUploadType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            /** @var UploadedFile $file */
            $file = $data['file'];

            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                $this->addFlash('danger', $translator->trans('uploaded_file_not_allowed'));

                return $this->redirectToRoute('product_add_image', ['id' => $product->getId()]);
            }

            $fileName = Str::asSnakeCase($product->getName()).'_'.md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move($this->getParameter('public_dir').$this->getParameter('images_dir'), $fileName);
            } catch (FileException $e) {
                $logger->error($e->getMessage(), $e->getTrace());

                $this->addFlash('danger', $translator->trans('could_not_upload'));

                return $this->redirectToRoute('product_add_image', ['id' => $product->getId()]);
            }

            $addImageCommand = new AddImageCommand($fileName, $product->getId());
            $resizeCommand = new ResizeImageCommand($fileName);

            $bus->dispatch($addImageCommand);
            $bus->dispatch($resizeCommand);

            return $this->redirectToRoute('product_add_image', ['id' => $product->getId()]);
        }

        return $this->render(
            'product/upload_image.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]
        );
    }

    /**
     * @Route("/{id}/delete/image/{imageId}", name="product_delete_image", requirements={"id" = ".+", "imageId" = ".+"})
     */
    public function deleteImage(string $id, string $imageId, MessageBusInterface $bus)
    {
        $productQuery = new ProductQuery($id);

        /** @var ProductView $product */
        $product = $bus->dispatch($productQuery);

        $deleteImageCommand = new DeleteImageCommand($imageId, $product->getId());

        $bus->dispatch($deleteImageCommand);

        return $this->redirectToRoute('product_add_image', ['id' => $product->getId()]);
    }
}
