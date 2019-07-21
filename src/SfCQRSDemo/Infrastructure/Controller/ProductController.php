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
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/product")
 */
class ProductController extends BaseController
{
    /**
     * @Route("/detail/{id}", name="product_detail", requirements={"id" = ".+"})
     *
     * @param string $id
     *
     * @return Response
     */
    public function detail(string $id): Response
    {
        $productQuery = new ProductQuery($id);

        $product = $this->handleMessage($productQuery);

        return $this->render(
            'product/detail.html.twig',
            ['product' => $product]
        );
    }

    /**
     * @Route("/update/{id}", name="product_update", requirements={"id" = ".+"})
     *
     * @param string  $id
     * @param Request $request
     *
     * @return Response
     */
    public function update(string $id, Request $request)
    {
        $productQuery = new ProductQuery($id);

        /** @var ProductView $product */
        $product = $this->handleMessage($productQuery);

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

            $this->handleMessage($updateCommand);

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
     * @param Request $request
     *
     * @return Response
     */
    public function add(Request $request)
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

            $this->handleMessage($productCommand);

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
     * @param TranslatorInterface $translator
     * @param LoggerInterface     $logger
     *
     * @return Response
     */
    public function addImage(
        string $id,
        Request $request,
        TranslatorInterface $translator,
        LoggerInterface $logger
    ): Response {
        $productQuery = new ProductQuery($id);

        /** @var ProductView $product */
        $product = $this->handleMessage($productQuery);

        $form = $this->createForm(ImageUploadType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            /** @var UploadedFile $file */
            $file = $data['file'];

            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!in_array($file->getMimeType(), $allowedTypes, true)) {
                $this->addFlash('danger', $translator->trans('uploaded_file_not_allowed'));

                return $this->redirectToRoute('product_add_image', ['id' => $product->getId()]);
            }

            $fileName = Str::asSnakeCase($product->getName()) . '_' . md5(uniqid('tt', true)) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('public_dir') . $this->getParameter('images_dir'), $fileName);
            } catch (FileException $e) {
                $logger->error($e->getMessage(), $e->getTrace());

                $this->addFlash('danger', $translator->trans('could_not_upload'));

                return $this->redirectToRoute('product_add_image', ['id' => $product->getId()]);
            }

            $addImageCommand = new AddImageCommand($fileName, $product->getId());
            $resizeCommand = new ResizeImageCommand($fileName);

            $this->handleMessage($addImageCommand);
            $this->dispatchMessage($resizeCommand);

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
     *
     * @param string $id
     * @param string $imageId
     *
     * @return RedirectResponse
     */
    public function deleteImage(string $id, string $imageId)
    {
        $productQuery = new ProductQuery($id);

        /** @var ProductView $product */
        $product = $this->handleMessage($productQuery);

        $deleteImageCommand = new DeleteImageCommand($imageId, $product->getId());

        $this->handleMessage($deleteImageCommand);

        return $this->redirectToRoute('product_add_image', ['id' => $product->getId()]);
    }
}
