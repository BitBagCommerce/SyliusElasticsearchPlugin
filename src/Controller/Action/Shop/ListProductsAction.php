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
use BitBag\SyliusElasticsearchPlugin\Finder\FinderInterface;
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
     * @var FinderInterface
     */
    private $shopProductsFinder;

    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * @param FormFactoryInterface $formFactory
     * @param DataHandlerInterface $shopProductListDataHandler
     * @param FinderInterface $shopProductsFinder
     * @param EngineInterface $templatingEngine
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        DataHandlerInterface $shopProductListDataHandler,
        FinderInterface $shopProductsFinder,
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
        $form = $this->formFactory->create(ShopProductsFilterType::class);
        $data = $this->shopProductListDataHandler->retrieveData($request);
        $products = $this->shopProductsFinder->find($data);
        $template = $request->get('template');

        $form->handleRequest($request);

        return $this->templatingEngine->renderResponse($template, [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }
}
