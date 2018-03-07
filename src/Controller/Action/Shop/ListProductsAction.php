<?php

/*
 * This file has been created by developers from BitBag. 
 * Feel free to contact us once you face any issues or want to start
 * another great project. 
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl. 
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinderInterface;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ShopProductsFilterType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ListProductsAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var DataHandlerInterface
     */
    private $shopProductListDataHandler;

    /**
     * @var ShopProductsFinderInterface
     */
    private $shopProductsFinder;

    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * @param FormFactoryInterface $formFactory
     * @param DataHandlerInterface $shopProductListDataHandler
     * @param ShopProductsFinderInterface $shopProductsFinder
     * @param EngineInterface $templatingEngine
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        DataHandlerInterface $shopProductListDataHandler,
        ShopProductsFinderInterface $shopProductsFinder,
        EngineInterface $templatingEngine
    )
    {
        $this->formFactory = $formFactory;
        $this->shopProductListDataHandler = $shopProductListDataHandler;
        $this->shopProductsFinder = $shopProductsFinder;
        $this->templatingEngine = $templatingEngine;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->createNamed(null, ShopProductsFilterType::class);
        $form->handleRequest($request);

        $requestData = array_merge(
            $form->getData(),
            $request->query->all(),
            ['slug' => $request->get('slug')]
        );
        $data = $this->shopProductListDataHandler->retrieveData($requestData);
        $products = $this->shopProductsFinder->find($data);
        $template = $request->get('template');

        return $this->templatingEngine->renderResponse($template, [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }
}
