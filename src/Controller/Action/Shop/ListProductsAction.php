<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\SortDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinderInterface;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ShopProductsFilterType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Twig\Environment;

final class ListProductsAction
{
    private FormFactoryInterface $formFactory;

    private DataHandlerInterface $shopProductListDataHandler;

    private SortDataHandlerInterface $shopProductsSortDataHandler;

    private PaginationDataHandlerInterface $paginationDataHandler;

    private ShopProductsFinderInterface $shopProductsFinder;

    private Environment $twig;

    public function __construct(
        FormFactoryInterface $formFactory,
        DataHandlerInterface $shopProductListDataHandler,
        SortDataHandlerInterface $shopProductsSortDataHandler,
        PaginationDataHandlerInterface $paginationDataHandler,
        ShopProductsFinderInterface $shopProductsFinder,
        Environment $twig
    ) {
        $this->formFactory = $formFactory;
        $this->shopProductListDataHandler = $shopProductListDataHandler;
        $this->shopProductsSortDataHandler = $shopProductsSortDataHandler;
        $this->paginationDataHandler = $paginationDataHandler;
        $this->shopProductsFinder = $shopProductsFinder;
        $this->twig = $twig;
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(ShopProductsFilterType::class);
        $form->handleRequest($request);
        $requestData = array_merge(
            $form->getData(),
            $request->query->all(),
            ['slug' => $request->get('slug')]
        );

        if (!$form->isValid()) {
            $requestData = $this->clearInvalidEntries($form, $requestData);
        }

        $data = array_merge(
            $this->shopProductListDataHandler->retrieveData($requestData),
            $this->shopProductsSortDataHandler->retrieveData($requestData),
            $this->paginationDataHandler->retrieveData($requestData)
        );

        $template = $request->get('template');
        $products = $this->shopProductsFinder->find($data);

        return new Response($this->twig->render($template, [
            'form' => $form->createView(),
            'products' => $products,
            'taxon' => $data['taxon'],
        ]));
    }

    private function clearInvalidEntries(FormInterface $form, array $requestData): array
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($form->getErrors(true) as $error) {
            $errorOrigin = $error->getOrigin();
            $propertyAccessor->setValue(
                $requestData,
                ($errorOrigin->getParent()->getPropertyPath() ?? '') . $errorOrigin->getPropertyPath(),
                ''
            );
        }

        return $requestData;
    }
}
