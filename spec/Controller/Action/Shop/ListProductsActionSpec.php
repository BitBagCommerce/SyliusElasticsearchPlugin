<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop\ListProductsAction;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\DataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\SortDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinderInterface;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ShopProductsFilterType;
use Pagerfanta\Pagerfanta;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class ListProductsActionSpec extends ObjectBehavior
{
    function let(
        FormFactoryInterface $formFactory,
        DataHandlerInterface $shopProductListDataHandler,
        SortDataHandlerInterface $shopProductsSortDataHandler,
        PaginationDataHandlerInterface $paginationDataHandler,
        ShopProductsFinderInterface $shopProductsFinder,
        Environment $twig
    ): void {
        $this->beConstructedWith(
            $formFactory,
            $shopProductListDataHandler,
            $shopProductsSortDataHandler,
            $paginationDataHandler,
            $shopProductsFinder,
            $twig
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ListProductsAction::class);
    }

    function it_renders_product_list(
        Request $request,
        FormFactoryInterface $formFactory,
        FormInterface $form,
        ParameterBag $queryParameters,
        DataHandlerInterface $shopProductListDataHandler,
        SortDataHandlerInterface $shopProductsSortDataHandler,
        PaginationDataHandlerInterface $paginationDataHandler,
        ShopProductsFinderInterface $shopProductsFinder,
        Pagerfanta $pagerfanta,
        FormView $formView,
        Environment $twig,
        Response $response
    ): void {
        $form->getData()->willReturn([]);
        $form->isValid()->willReturn(true);
        $form->handleRequest($request)->willReturn($form);
        $form->createView()->willReturn($formView);
        $form->isSubmitted()->willReturn(true);

        $formFactory->create(ShopProductsFilterType::class)->willReturn($form);
        $request->query = $queryParameters;
        $queryParameters->all()->willReturn([]);

        $request->get('template')->willReturn('@Template');
        $request->get('slug')->willReturn(null);

        $shopProductListDataHandler->retrieveData(['slug' => null])->willReturn(['taxon' => null]);
        $shopProductsSortDataHandler->retrieveData(['slug' => null]);
        $paginationDataHandler->retrieveData(['slug' => null]);

        $shopProductsFinder->find(['taxon' => null])->willReturn($pagerfanta);

        $twig->render('@Template', Argument::any())->shouldBeCalled();

        $this->__invoke($request);
    }
}
